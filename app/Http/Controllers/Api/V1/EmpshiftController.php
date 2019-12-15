<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Shift;
use App\Api\Repositories\Contracts\EmpshiftRepository;
use App\Api\Repositories\Contracts\UserRepository;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
use App\Api\Entities\User;
use App\Api\Entities\Empshift;

//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Yaml\Tests\B;

class EmpshiftController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $empshiftRepository;

    protected  $userRepository;


    protected $auth;

    protected $request;

    public function __construct(
        EmpshiftRepository $empshiftRepository,
        UserRepository $userRepository,
        AuthManager $auth,
        Request $request
    ) {
        $this->empshiftRepository = $empshiftRepository;
        $this->userRepository=$userRepository;
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

    #region nhan vien dang ky ca
    public function registerEF()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'user'=>'string|required',
            'shift_name'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $user=$this->request->get('user');
        $shift_name=$this->request->get('shift_name');
        $userCheck=User::where(['name'=>$user])->first();
        $shiftCheck=Shift::where(['shift_name'=>$shift_name])->first();
        if(empty($userCheck)){
            return $this->errorBadRequest('Nhân viên không tồn tại');
        }
        if(empty($shiftCheck)){
            return $this->errorBadRequest('Ca làm chưa đăng ký');
        }

        $user_id=mongo_id($userCheck->_id);
        $shift_id=mongo_id($shiftCheck->_id);

        $attributes = [
            'user_id'=>$user_id,
            //'date_of_week'=>$this->request->get('date_of_week'),
            'shift_id'=>$shift_id,
        ];
        $empShift = $this->empshiftRepository->create($attributes);



        return $this->successRequest($empShift->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion



    #region xem ca
    public function viewEF()
    {
        // Validate Data import.
        /*$validator = \Validator::make($this->request->all(), [
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



        return $this->successRequest($dep);*/

        $empshift=$this->empshiftRepository->getEmpshift(["user_id"=>$this->request->get('id')]);
        return $this->successRequest($empshift);

        // return $this->successRequest($user->transform());
    }
    #endregion
}
