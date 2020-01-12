<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Branch;
use App\Api\Entities\Dep;
use App\Api\Repositories\Contracts\ShiftRepository;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
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
    protected $dayOfWorkRepository;

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
        $user=$this->user();
        // Validate Data import.
        $validator = \Validator::make($this->request->all(), [
            'branch_id'=>'nullable',
            'dep_id'=>'required',
            'position_id'=>'nullable',
            'shift_name'=>'required',
            'time_begin'=>'required|date_format:H:i',
            'time_end'=>'required|date_format:H:i',
            'work_date'=>'required|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $dep_id=$this->request->get('dep_id');
        $depCheck=Dep::where(['_id'=>mongo_id($dep_id)])->first();
        if(empty($depCheck)){
            return $this->errorBadRequest('Chưa có phòng ban');
        }
        $branch_id=mongo_id($depCheck->branch_id);
        $branchCheck=Branch::where(['_id'=>$branch_id])->first();
        $shop_id=mongo_id($branchCheck->shop_id);
        //tao time
        $time_begin=$this->request->get('time_begin');
        $time_end=$this->request->get('time_end');
        //tao ngay lam
        $work_date=$this->request->get('work_date');
        if(is_array($work_date))
        {
            foreach($work_date as $row)
            {
                $dt=Carbon::createFromDate($row);
                if($dt->isSunday()==false)
                $attributes = [
                    'shift_name'=>$this->request->get('shift_name'),
                    'shop_id'=>$shop_id,
                    'branch_id'=>$branch_id,
                    'dep_id'=>$dep_id,
                    'work_date'=>$row,
                    'time_begin'=>$time_begin,
                    'time_end'=>$time_end,
                ];
                $shift = $this->shiftRepository->create($attributes);
                return $this->successRequest($shift->transform());
            }
        }
        else
        {
            $attributes = [
                'shift_name'=>$this->request->get('shift_name'),
                'shop_id'=>$shop_id,
                'branch_id'=>$branch_id,
                'dep_id'=>$dep_id,
                'work_date'=>$work_date,
                'time_begin'=>$time_begin,
                'time_end'=>$time_end,
            ];
            $shift = $this->shiftRepository->create($attributes);
            return $this->successRequest($shift->transform());
        }
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
//     public function viewShift()
//     {
//         // Validate Data import.
//         /*$validator = \Validator::make($this->request->all(), [
//             'branchname'=>'required',
//         ]);
//         if ($validator->fails()) {
//             return $this->errorBadRequest($validator->messages()->toArray());
//         }

//         $branchname=$this->request->get('branchname');
//         // Kiểm tra xem email đã được đăng ký trước đó chưa

//         $branchCheck=Branch::where(['branchName'=>$branchname])->first();
//         if(empty($branchCheck)) {
//             return $this->errorBadRequest(trans('Chi nhánh không có sẵn'));
//         }

//         $branchid=mongo_id($branchCheck->_id);

//         $dep = Dep::where(['branch_id'=>$branchid])->paginate();



//         return $this->successRequest($dep);*/
// //        $shift=$this->shiftRepository->findByField('work_date','Thứ 6, 20-12-2019');
//         $shifts=Shift::all()->groupBy('work_date');
//         $data=[

//         ];
//         foreach ($shifts as $shift){
//             $data[]=$shift->transform();
//         }
              


//         return $this->successRequest($data);



//         // return $this->successRequest($user->transform());
//     }
    #endregion
    public  function listShift(){
        $user=$this->user();
        $shop_id=$user->shop_id;

        $validator = \Validator::make($this->request->all(), [
            'dep_id'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }
        $dep_check=Dep::where(['_id'=>$this->request->get('dep_id')])->first();
        $branch_id=$dep_check->branch_id;
        $shifts=Shift::where(['dep_id'=>$this->request->get('dep_id'),'branch_id'=>$branch_id,'shop_id'=>$shop_id])->get();
        // dd($shifts);
        // $shifts=$this->shiftRepository->all();
       $data=[];
       foreach($shifts as $shift)
       {
           $data[]=$shift->transform();
       }
        return $this->successRequest($data);
    }
}
