<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Dep;
use App\Api\Repositories\Contracts\ShiftRepository;
//use App\Api\Repositories\Contracts\DepRepository;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
//use App\Api\Entities\Dep;
use App\Api\Entities\Shift;

//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Yaml\Tests\B;

class ShiftController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $shiftRepository;

    /**
     * @var ShopRepository
     */


    protected $auth;

    protected $request;

    public function __construct(
        ShiftRepository $shiftRepository,
        AuthManager $auth,
        Request $request
    ) {
        $this->shiftRepository = $shiftRepository;
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

    #region tao ca lam
    public function registerShift()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'dep_name'=>'required',
            'shift_name'=>'required',
            'time_begin'=>'required|date_format:H:i',
            'time_end'=>'required|date_format:H:i'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $depName=$this->request->get('dep_name');
        $depCheck=Dep::where(['depName'=>$depName])->first();
        if(empty($depCheck)){
            return $this->errorBadRequest('Chưa có phòng ban');
        }

        $dep_id=mongo_id($depCheck->_id);

        // Tạo shop trước
        $attributes = [
            'shift_name'=>$this->request->get('shift_name'),
            'time_begin'=>$this->request->get('time_begin'),
            'time_end'=>$this->request->get('time_end'),
            'dep_id'=>$dep_id,
        ];
        $shift = $this->shiftRepository->create($attributes);



        return $this->successRequest($shift->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xoa ca làm
    public function delShift()
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
        $idCheck = Shift::where(['_id' => $id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Ca làm không tồn tại'));
        }

        // Tạo shop trước
        $idCheck->delete();



        return $this->successRequest();

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region sua làm
    public function editShift()
    {
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'id'=>'required',
            'shift_name'=>'required',
            'time_begin'=>'required|date_format:H:i',
            'time_end'=>'required|date_format:H:i'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $id=$this->request->get('id');
        // Kiểm tra xem ca làm đã được đăng ký trước đó chưa

        $idCheck=Shift::where(['_id'=>$id])->first();
        if(empty($idCheck)) {
            return $this->errorBadRequest(trans('Ca làm không tồn tại'));
        }


        // lấy thuộc tính
        $attributes = [
            'shift_name'=>$this->request->get('shift_name'),
            'time_begin'=>$this->request->get('time_begin'),
            'time_end'=>$this->request->get('time_end'),
        ];
        $shift = $this->shiftRepository->update($attributes,$id);



        return $this->successRequest($shift->transform());

        // return $this->successRequest($user->transform());
    }
    #endregion

    #region xem ca lam
    public function viewShift()
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

        $shift=$this->shiftRepository->getShift(["shift_name"=>$this->request->get('id')]);
        return $this->successRequest($shift);

        // return $this->successRequest($user->transform());
    }
    #endregion
}
