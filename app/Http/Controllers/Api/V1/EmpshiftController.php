<?php

namespace App\Http\Controllers\Api\V1;

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

    #region vao ca
    public function registerEF()
    {
        // Validate Data import.
        /*$validator = \Validator::make($this->request->all(), [
            'user'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $user=$this->request->get('user');
        $userCheck=User::where([])*/

        $dateCheckin=date("Y-m-d");
        $timeIn=date("h:i");
        $timeOut=null;
        // Tạo shop trước
        $attributes = [
            'date_checkin'=>$dateCheckin,
            'time_in'=>$timeIn,
            'time_out'=>$timeOut
        ];
        $empShift = $this->empshiftRepository->create($attributes);



        return $this->successRequest($empShift->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xoa ca làm
    public function delEF()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $id = $this->request->get('id');
        // Kiểm tra xem ca đã được đăng ký trước đó chưa
        $idCheck = Empshift::where(['_id' => $id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Ca làm không tồn tại'));
        }

        // Tạo shop trước
        $idCheck->delete();



        return $this->successRequest();

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region ra ca
    public function editEF()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $id=$this->request->get('id');
        // Kiểm tra xem ca làm đã được đăng ký trước đó chưa

        $idCheck=Empshift::where(['_id'=>$id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Ca làm không tồn tại'));
        }

        $timeOut=date("h:i");
        // lấy thuộc tính
        $attributes = [
            'time_out'=>$timeOut,
        ];
        $empshift = $this->empshiftRepository->update($attributes,$id);



        return $this->successRequest($empshift->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xem phong ban
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

        $empshift=$this->empshiftRepository->getEmpshift(["date_checkin"=>$this->request->get('id')]);
        return $this->successRequest($empshift);

        // return $this->successRequest($user->transform());
    }
    #endregion
}
