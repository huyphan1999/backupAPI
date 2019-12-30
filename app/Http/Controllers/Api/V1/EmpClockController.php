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
        $user=$this->user();
        $shift_id=$this->request->get('shift_id');
        //Lấy thời gian lúc nhân viên bấm
        $time=Carbon::now();
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
                $attribute=[
                    'user_id'=>$user->_id,
                    'shift_id'=>$shift_id,
                    'time_in'=>$time,
                ];
                $shift->clicked=1;
                $shift->save();
                $emp_clock=EmpClock::updateOrCreate([
                    'user_id'=>$user->_id,
                    'shift_id'=>$shift_id,
                ],[
                    'time_in'=>$time,
                ]);
                break;
            }
            case 1:
            {
                $attribute=[
                    'user_id'=>$user->_id,
                    'shift_id'=>$shift_id,
                    'time_out'=>$time
                ];
                $emp_clock=EmpClock::updateOrCreate([
                    'user_id'=>$user->_id,
                    'shift_id'=>$shift_id,
                ],[
                    'time_out'=>$time,
                ]);
                $shift->clicked=0;
                $shift->save();
                break;
            }
        }
       if(!empty($emp_clock))
       {
           $emp_clock=$this->empclockRepository->create($attribute);
       }
       else{
           $emp_clock=$this->empclockRepository->update($attribute,$user->_id);
       }
       $emp_clock=$this->empclockRepository->updateOrCreate($attribute,['user_id'=>$user->_id,'shift_id'=>$shift_id]);
       $emp_clock=EmpClock::updateOrCreate([
           'user_id'=>$user->_id,
           'shift_id'=>$shift_id,
       ],[
          ''
       ]);
        return $this->successRequest($emp_clock);

    }

    public function TimeIn()
    {
        $user=$this->user();
        $shift_id=$this->request->get('shift_id');
        //Lấy thời gian lúc nhân viên bấm
        $time=Carbon::now('Asia/Ho_Chi_Minh');
<<<<<<< HEAD
=======
        $shift=EmpShift::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
>>>>>>> 67ff75c577e71488faeaca240ea2a11a83b7b8e1
//        $emp_clock=$this->empclockRepository->findWhere([
//            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
//        ])->first();
//        dd($emp_clock);
//        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
<<<<<<< HEAD
        $status=0;

        //ham ktra xem da vao ca hay chua
        
        $clock_check=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id),'status'=>1])->first();

        if(empty($clock_check)){
            $status=1;
            $attribute=[
                'user_id'=>$user->_id,
                'shift_id'=>$shift_id,
                'time_in'=>$time->toDateTimeString(),
                'time_out'=>NULL,
                'status'=>$status,
            ];
            $emp_clock=$this->empclockRepository->create($attribute);
        }
        else{
            $attribute=[
                'time_out'=>$time->toDateTimeString(),
                'status'=>$status,
            ];
            $emp_clock=$this->empclockRepository->update($attribute,$clock_check->_id);
        }
        
=======
        
        $attribute=[
            'user_id'=>$user->_id,
            'shift_id'=>$shift_id,
            'time_in'=>$time->toDateTimeString(),
            'time_out'=>NULL,
        ];
        $emp_clock=$this->empclockRepository->create($attribute);
>>>>>>> 67ff75c577e71488faeaca240ea2a11a83b7b8e1
        return $this->successRequest($emp_clock->transform());

    }

    public function TimeOut()
    {
        $user=$this->user();
        $shift_id=$this->request->get('shift_id');
        //Lấy thời gian lúc nhân viên bấm
<<<<<<< HEAD
        $time=Carbon::now();
=======
        Carbon::resetToStringFormat();
        $time=Carbon::now('Asia/Ho_Chi_Minh');
>>>>>>> 67ff75c577e71488faeaca240ea2a11a83b7b8e1

        
        $empclock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id),'time_out'=>NULL])->first();
//        $emp_clock=$this->empclockRepository->findWhere([
//            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
//        ])->first();
//        dd($emp_clock);
//        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
        
        $attribute=[
            'time_out'=>$time->toDateTimeString(),
        ];
        $emp_clock=$this->empclockRepository->update($attribute,$empclock->_id);
        return $this->successRequest($emp_clock->transform());

    }
<<<<<<< HEAD

    public function Time()
    {
        $status=0;
        $user=$this->user();
        $shift_id=$this->request->get('shift_id');
        //Lấy thời gian lúc nhân viên bấm
        $time=Carbon::now();

        
        $empclock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id),'status'=>0])->first();
//        $emp_clock=$this->empclockRepository->findWhere([
//            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
//        ])->first();
//        dd($emp_clock);
//        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
        if(!empty($empclock)){

        }
        $attribute=[
        ];
        $emp_clock=$this->empclockRepository->update($attribute,$empclock->_id);
        return $this->successRequest($emp_clock->transform());
    }
=======
>>>>>>> 67ff75c577e71488faeaca240ea2a11a83b7b8e1
}