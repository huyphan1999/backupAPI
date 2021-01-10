<?php


namespace App\Http\Controllers\Api\V1;


use Carbon\Carbon;
use App\Api\Repositories\Contracts\EmpClockRepository;
use App\Api\Repositories\Contracts\SalaryRepository;
use App\Api\Repositories\Contracts\HistoryRepository;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
use App\Api\Entities\EmpClock;
use App\Api\Entities\Empshift;
use App\Api\Entities\Shift;

//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class EmpClockController extends Controller
{
    protected $empshiftRepository;

    protected  $userRepository;
    protected $empclockRepository;
    protected $salaryRepository;
    protected $historyRepository;
    protected $shiftRepository;

    protected $auth;

    protected $request;
    public function __construct(
        AuthManager $auth,
        Request $request,
        EmpClockRepository $empClockRepository,
        SalaryRepository $salaryRepository,
        HistoryRepository $historyRepository
    ) {
        $this->request = $request;
        $this->auth = $auth;
        $this->empclockRepository = $empClockRepository;
        $this->salaryRepository = $salaryRepository;
        $this->historyRepository = $historyRepository;
        parent::__construct();
    }

    //     public function TimeKeeping()
    //     {
    //         $user=$this->user();
    //         $shift_id=$this->request->get('shift_id');
    //         //Lấy thời gian lúc nhân viên bấm
    //         $time=Carbon::now();
    //         $shift=EmpShift::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
    // //        $emp_clock=$this->empclockRepository->findWhere([
    // //            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
    // //        ])->first();
    // //        dd($emp_clock);
    // //        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
    //         switch($shift->clicked)
    //         {
    //             case 0:
    //             {
    //                 $attribute=[
    //                     'user_id'=>$user->_id,
    //                     'shift_id'=>$shift_id,
    //                     'time_in'=>$time,
    //                 ];
    //                 $shift->clicked=1;
    //                 $shift->save();
    //                 $emp_clock=EmpClock::updateOrCreate([
    //                     'user_id'=>$user->_id,
    //                     'shift_id'=>$shift_id,
    //                 ],[
    //                     'time_in'=>$time,
    //                 ]);
    //                 break;
    //             }
    //             case 1:
    //             {
    //                 $attribute=[
    //                     'user_id'=>$user->_id,
    //                     'shift_id'=>$shift_id,
    //                     'time_out'=>$time
    //                 ];
    //                 $emp_clock=EmpClock::updateOrCreate([
    //                     'user_id'=>$user->_id,
    //                     'shift_id'=>$shift_id,
    //                 ],[
    //                     'time_out'=>$time,
    //                 ]);
    //                 $shift->clicked=0;
    //                 $shift->save();
    //                 break;
    //             }
    //         }
    //        if(!empty($emp_clock))
    //        {
    //            $emp_clock=$this->empclockRepository->create($attribute);
    //        }
    //        else{
    //            $emp_clock=$this->empclockRepository->update($attribute,$user->_id);
    //        }
    //        $emp_clock=$this->empclockRepository->updateOrCreate($attribute,['user_id'=>$user->_id,'shift_id'=>$shift_id]);
    //        $emp_clock=EmpClock::updateOrCreate([
    //            'user_id'=>$user->_id,
    //            'shift_id'=>$shift_id,
    //        ],[
    //           ''
    //        ]);
    //         return $this->successRequest($emp_clock);

    //     }

    public function checkIn()
    {
        //        Log::debug('test0');
        $user = $this->user();
        $user_id = $user->id;



        //Lấy ca của user trong ngày
        $from  = Carbon::now()
            ->startOfDay()        // 2018-09-29 00:00:00.000000
            ->toDateTime(); // 2018-09-29 00:00:00

        $to    = Carbon::now()
            ->endOfDay()          // 2018-09-29 23:59:59.000000
            ->toDateTime(); // 2018-09-29 23:59:59



        // dd($from, $to);
        $emp_shift = Empshift::whereBetween('working_date', [$from, $to])
            ->where(['user_id' => mongo_id($user_id), 'isCheckOut' => false])
            ->first();
        // dd($emp_shift);



        //lay thong tin ca lam

        $emp_shift_id = null;
        $shift_check = null;
        $shift_id = null;
        $working_date = null;
        $shift_name = null;
        $shift_time = null;

        if (!empty($emp_shift)) {
            $emp_shift_id = $emp_shift->_id;
            $shift_check = $emp_shift->shift();
            $shift_id = $shift_check->_id;
            $working_date = $shift_check->working_date;
            $shift_name = $shift_check->name;
            $shift_time = ($shift_check->time_begin) . '-' . ($shift_check->time_end);
        }


        // dd($shift_time);

        //Lấy thời gian lúc nhân viên bấm
        $now = Carbon::now();

        //Nếu nhân viên chưa chấm công
        $status = 0;

        //ham ktra xem da vao ca hay chua

        $clock_check =
            EmpClock::where([
                'user_id' => mongo_id($user->_id),
                'isCheckOut' => false
            ])
            ->first();


        //Chưa vào ca
        if (empty($clock_check)) {
            $status = 1;
            $attribute = [
                'user_id' => mongo_id($user->_id),
                'shift_id' => mongo_id($shift_id),
                'emp_shift_id' => mongo_id($emp_shift_id),
                'time_in' => $now,
                'time_out' => null,
                'status' => $status,
                'isCheckOut' => false,
            ];

            $data = [
                'user_id' => mongo_id($user->_id),
                'user_name' => $user->name,
                'working_date' => $working_date,
                'emp_shift_id' => mongo_id($emp_shift_id),
                'shift_name' => $shift_name,
                'shift_time' => $shift_time,
                'emp_shift_id' => mongo_id($emp_shift_id),
                'time_check' => $now,
                'status' => $status,
                'type' => 'check_in'
            ];
            // dd($data);
            $emp_clock = $this->empclockRepository->create($attribute);
            $emp_history = $this->historyRepository->create($data);
        } else {
            $attribute = [
                'time_out' => $now,
                'status' => $status,
                'isCheckOut' => true,
            ];
            $emp_clock = $this->empclockRepository->update($attribute, $clock_check->_id);
            //tạo giờ công
            // $salary_attribute = $this->empclockRepository->createSalary($emp_clock);
            //tạo record trong collection work_salary
            // $create_salary = $this->salaryRepository->create($salary_attribute);
            $data = [
                'user_id' => mongo_id($user->_id),
                'user_name' => $user->name,
                'working_date' => $working_date,
                'emp_shift_id' => mongo_id($emp_shift_id),
                'shift_name' => $shift_name,
                'shift_time' => $shift_time,
                'emp_shift_id' => mongo_id($emp_shift_id),
                'time_check' => $now,
                'status' => $status,
                'type' => 'check_out'
            ];
            $emp_clock = $this->empclockRepository->update($attribute, $clock_check->_id);
            $emp_history = $this->historyRepository->create($data);
            // $emp_clock=$this->empclockRepository->create($attribute);
        }
        return $this->successRequest($emp_clock->transform());
    }

    //     public function TimeOut()
    //     {
    //         $user=$this->user();
    //         $shift_id=$this->request->get('shift_id');
    //         //Lấy thời gian lúc nhân viên bấm
    //         $time=Carbon::now();
    //         $empclock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id),'time_out'=>NULL])->first();
    // //        $emp_clock=$this->empclockRepository->findWhere([
    // //            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
    // //        ])->first();
    // //        dd($emp_clock);
    // //        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();

    //         $attribute=[
    //             'time_out'=>$time->toDateTimeString(),
    //         ];
    //         $emp_clock=$this->empclockRepository->update($attribute,$empclock->_id);
    //         return $this->successRequest($emp_clock->transform());

    //     }

    //     public function Time()
    //     {
    //         $status=0;
    //         $user=$this->user();
    //         $shift_id=$this->request->get('shift_id');
    //         //Lấy thời gian lúc nhân viên bấm
    //         $time=Carbon::now();


    //         $empclock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id),'status'=>0])->first();
    // //        $emp_clock=$this->empclockRepository->findWhere([
    // //            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
    // //        ])->first();
    // //        dd($emp_clock);
    // //        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
    //         if(!empty($empclock)){

    //         }
    //         $attribute=[
    //         ];
    //         $emp_clock=$this->empclockRepository->update($attribute,$empclock->_id);
    //         return $this->successRequest($emp_clock->transform());
    //     }
}
