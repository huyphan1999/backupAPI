<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Repositories\Contracts\BranchRepository;
use App\Api\Repositories\Contracts\ShopRepository;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
use App\Api\Entities\Shop;
use App\Api\Entities\Branch;

//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $branchRepository;

    /**
     * @var ShopRepository
     */
    protected $shopRepository;

    protected $auth;

    protected $request;

    public function __construct(
        BranchRepository $branchRepository,
        ShopRepository $shopRepository,
        AuthManager $auth,
        Request $request
    ) {
        $this->branchRepository = $branchRepository;
        $this->shopRepository = $shopRepository;
        $this->request = $request;
        $this->auth = $auth;
        parent::__construct();
    }
    /**
     * @api {post} /shop/register 1. Register Shop
     * @apiDescription (Register Shop)
     * @apiGroup Employee
     * @apiParam {String} name  Name of user
     * @apiParam {Email} email  Login email.
     * @apiParam {String} password Login password
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     * {
     *
     * }
     * }
     */

    #region tao chi nhanh
    public function registerBranch()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'name'=>'required',
            'branchName' => 'required',
            'address'=> 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $name = $this->request->get('name');
        $branchname=$this->request->get('branchName');
        // Kiểm tra xem công ty đã được đăng ký trước đó chưa
        $shopCheck = Shop::where(['name' => $name])->first();
        //Kiểm tra xem đã có phòng ban chưa?
        $branchCheck=Branch::where(['branchName'=>$branchname])->first();
        if(empty($shopCheck)) {
            return $this->errorBadRequest(trans('Công ty chưa đăng ký'));
        }
        else{
            if(!empty($branchCheck)){
                return $this->errorBadRequest(trans('Chi nhánh đã tồn tại'));
            }
        }

        // thuộc tính của phòng ban
        $attributes = [
            'branchName' => $this->request->get('branchName'),
            'address' => $this->request->get('address'),
            'is_web' => (int)($this->request->get('is_web')),
            'shop_id'=>mongo_id($shopCheck->_id),
        ];
        $branch = $this->branchRepository->create($attributes);



        return $this->successRequest($branch->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xoa chi nhanh
    public function delBranch()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }
        //lấy id
        $id = $this->request->get('id');
        // Kiểm tra xem id đã được đăng ký trước đó chưa
        $idCheck = Branch::where(['_id' => $id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Chi nhánh không tồn tại'));
        }

        // Tạo shop trước
        $idCheck->delete();



        return $this->successRequest();

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region sua chi nhanh
    public function editBranch()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required',
            'branchName' => 'required',
            'address'=> 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $id=$this->request->get('id');
        // Kiểm tra xem id đã được đăng ký trước đó chưa

        $idCheck=Branch::where(['_id'=>$id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Chi nhánh không tồn tại'));
        }


        // lấy thông tin để sửa
        $attributes = [
            'branchName' => $this->request->get('branchName'),
            'address' => $this->request->get('address'),
        ];
        $branch = $this->branchRepository->update($attributes,$id);



        return $this->successRequest($branch->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xem chi nhanh
    public function viewBranch()
    {
        // Validate Data import.
        /*$validator = \Validator::make($this->request->all(), [
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $name=$this->request->get('name');
        // Kiểm tra xem email đã được đăng ký trước đó chưa

        $companyCheck=Shop::where(['name'=>$name])->first();
        if(empty($companyCheck)) {
            return $this->errorBadRequest(trans('Công ty chưa đăng ký'));
        }

        $companyid=mongo_id($companyCheck->_id);


        $branch = $this->branchRepository->findByField('shop_id',$companyid);




        return $this->successRequest($branch);*/

        $branch=$this->branchRepository->getBranch(["branchName"=>$this->request->get('id')]);
        return $this->successRequest($branch);
        // return $this->successRequest($user->transform());
    }
    #endregion
}
