<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Repositories\Contracts\BranchRepository;
use App\Api\Repositories\Contracts\DepRepository;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\PositionController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
use App\Api\Entities\Dep;
use App\Api\Entities\Branch;
use App\Api\Entities\User;

//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Yaml\Tests\B;

class DepController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $branchRepository;

    /**
     * @var ShopRepository
     */
    protected $depRepository;

    protected $auth;

    protected $request;

    public function __construct(
        BranchRepository $branchRepository,
        DepRepository $depRepository,
        AuthManager $auth,
        Request $request
    ) {
        $this->branchRepository = $branchRepository;
        $this->depRepository = $depRepository;
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

    #region tao phong ban
    public function registerDep()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'branch_id' => 'required',
            'dep_name'=> 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $depname=$this->request->get('dep_name');
        $branchCheck = Branch::where(['_id'=>mongo_id($this->request->get('branch_id'))])->first();
        $depCheck=Dep::where(['dep_name'=>$depname])->first();
        if(empty($branchCheck)) {
            return $this->errorBadRequest(trans('Chi nhánh không tồn tại'));
        }
        else{
            if(!empty($depCheck) && $branchCheck==$depCheck->branchName){
                return $this->errorBadRequest(trans('Phòng ban đã tồn tại'));
            }
        }

        $attributes = [
            'dep_name'=>$depname,
            'is_web' => (int)($this->request->get('is_web')),
            'branch_id'=>mongo_id($branchCheck->_id),
            'shop_id'=>mongo_id($branchCheck->shop_id)
        ];
        $dep = $this->depRepository->create($attributes);



        return $this->successRequest($dep);

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xoa phong ban
    public function delDep()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $id = $this->request->get('id');
        // Kiểm tra xem email đã được đăng ký trước đó chưa
        $idCheck = Dep::where(['_id' => $id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Phòng ban không tồn tại'));
        }

        // Tạo shop trước
        $idCheck->delete();



        return $this->successRequest();

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region sua phong ban
    public function editDep()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required',
            'depName'=> 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $id=$this->request->get('id');
        // Kiểm tra xem email đã được đăng ký trước đó chưa

        $idCheck=Dep::where(['_id'=>$id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Phòng ban không tồn tại'));
        }


        // Tạo shop trước
        $attributes = [
            'depName' => $this->request->get('depName'),
        ];
        $dep = $this->depRepository->update($attributes,$id);



        return $this->successRequest($dep->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xem phong ban
    public function viewDep()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'branchname'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $branchname=$this->request->get('branchname');
        // Kiểm tra xem email đã được đăng ký trước đó chưa

        $branchCheck=Branch::where(['branchName'=>$branchname])->first();
        if(empty($branchCheck)) {
            return $this->errorBadRequest(trans('Chi nhánh không có sẵn'));
        }

        $branchid=mongo_id($branchCheck->_id);

        $dep = Dep::where(['branch_id'=>$branchid])->paginate();



        return $this->successRequest($dep);

        // return $this->successRequest($user->transform());
    }
    #endregion
    public function deleteDep()
    {
        $id=$this->request->get('id');
        $dep=Dep::where('_id',mongo_id($id))->first();
        $user=User::where('dep_id',mongo_id($dep->_id))->update(['dep_id'=>null]);
        return $this->successRequest($user);
    }
}
