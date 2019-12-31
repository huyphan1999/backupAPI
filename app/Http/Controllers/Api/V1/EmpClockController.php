<?php


namespace App\Http\Controllers\Api\V1;


use Carbon\Carbon;
use App\Api\Entities\Shift;
use App\Api\Repositories\Contracts\EmpshiftRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\EmpClockRepository;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
use App\Api\Entities\User;
use App\Api\Entities\Empshift;
use App\Api\Entities\EmpClock;
//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class EmpClockController extends Controller
{
    protected $empshiftRepository;

    protected  $userRepository;
    protected $empclockRepository;

    protected $auth;

    protected $request;
    public function __construct(  AuthManager $auth,
                                  Request $request,
                                  EmpClockRepository $empClockRepository)
    {
        $this->request = $request;
        $this->auth = $auth;
        $this->empclockRepository=$empClockRepository;
        parent::__construct();
    }

    public function TimeKeeping()
    {
        $emp_clock=[];
        $user=$this->user();
        $shift_id=$this->request->get('shift_id');
        //Lấy thời gian lúc nhân viên bấm
        $time=Carbon::now('Asia/Ho_Chi_Minh');
        $shift=EmpShift::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
//        $emp_clock=$this->empclockRepository->findWhere([
//            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
//        ])->first();
//        dd($emp_clock);
//        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
        switch($shift->clicked)
        {
            case 0:
            {
                $shift->clicked=1;
                $shift->save();
                $check_empclock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
                if(!empty($check_empclock))
                {
                    break;
                }
                $attribute=[
                    'user_id'=>$user->_id,
                    'shift_id'=>$shift_id,
                    'time_in'=>$time->toDateTimeString(),
                ];
                $emp_clock=$this->empclockRepository->create($attribute);
                break;
            }
            case 1:
            {
                $shift->clicked=0;
                $shift->save();
                $attribute=[
                    'user_id'=>$user->_id,
                    'shift_id'=>$shift_id,
                    'time_out'=>$time->toDateTimeString()
                ];
                $emp_clock=$this->empclockRepository->create($attribute);
                break;
            }
        }
        $work_time=[];
        $time=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->get();
        foreach($time as $timein)
        {
            if(!empty($timein->timein))
            {
                $data['time_in']=$timein->timein;
            }
        }

        return $this->successRequest(['status'=>$shift->clicked,'history'=>$emp_clock,'time'=>$time]);
    }
    public function

}