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
            'shop_id'=>'required',
            'branch_name' => 'required',
            'address'=> 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }


        $branchname=$this->request->get('branch_name');
        // Kiểm tra xem email đã được đăng ký trước đó chưa
        $shopCheck = Shop::where(['_id' => mongo_id($this->request->get('shop_id'))])->first();
        $branchCheck=Branch::where(['branch_name'=>$branchname])->first();
        if(empty($shopCheck)) {
            return $this->errorBadRequest(trans('Công ty chưa đăng ký'));
        }
        else{
            if(!empty($branchCheck)){
                return $this->errorBadRequest(trans('Chi nhánh đã tồn tại'));
            }
        }

        $attributes = [
            'branch_name' => $this->request->get('branch_name'),
            'address' => $this->request->get('address'),
            'shop_id'=>mongo_id($shopCheck->_id),
        ];
        $branch = $this->branchRepository->create($attributes);
        return $this->successRequest($branch->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region sua chi nhanh
    public function updateBranch()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required',
            'branch_name' => 'required',
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
            'branch_name' => $this->request->get('branch_name'),
            'address' => $this->request->get('address'),
        ];
        $branch = $this->branchRepository->update($attributes,$id);
        return $this->successRequest($branch->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion
    public function listBranch()
    {
        $branches=$this->branchRepository->all();
        $data=[];
        foreach($branches as $branch)
        {
            $data[]=$branch->transform();
        }
        return $this->successRequest($data);
    }
    #region xem chi nhanh
    public function detailBranch()
    {
        $id=$this->request->get('id');
        $idCheck=Branch::where(['_id'=>$id])->first();
        return $this->successRequest($idCheck->transform());
    }
    public function searchBranch()
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

        $branch=$this->branchRepository->getBranch(["branch_name"=>$this->request->get('id')]);
        return $this->successRequest($branch);
        // return $this->successRequest($user->transform());
    }
    #endregion
    public function deleteBranch()
    {
        $id=$this->request->get('id');
        $branch=Branch::where('_id',mongo_id($id))->delete();
        return $this->successRequest($branch);
    }
}
