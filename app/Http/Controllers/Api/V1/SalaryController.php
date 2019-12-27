<?php

namespace App\Http\Controllers\Api\V1;


use Carbon\Carbon;
use App\Api\Entities\Shift;
use App\Api\Repositories\Contracts\EmpshiftRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\EmpClockRepository;
use App\Api\Repositories\Contracts\SalaryRepository;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
use App\Api\Entities\User;
use App\Api\Entities\Empshift;
use App\Api\Entities\EmpClock;
use App\Api\Entities\Salary;
//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class SalaryController extends Controller
{
    protected $empshiftRepository;
    protected  $userRepository;
    protected $empclockRepository;
    protected $salaryRepository;

    protected $auth;

    protected $request;
    public function __construct(  AuthManager $auth,
                                  Request $request,
                                  SalaryRepository $salaryRepository)
    {
        $this->request = $request;
        $this->auth = $auth;
        $this->salaryRepository=$salaryRepository;
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
    
    public function viewSalary()
    {
        // $user=$this->user();
        // $shift_id=$this->request->get('shift_id');
        $user="5df9f0e70bcc9818fe0b729a";
        $shift_id="5e02e8fb0bcc9847fd2ef077";
        $shift=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user)])->first();
//        $emp_clock=$this->empclockRepository->findWhere([
//            'shift_id'=>mongo_id($shift_id),'user_id'=>mongo_id($user->_id)
//        ])->first();
//        dd($emp_clock);
//        $emp_clock=EmpClock::where(['shift_id'=>($shift_id),'user_id'=>($user->_id)])->first();
        
        $time_in=$shift->time_in;
        $time_out=$shift->time_out;
        $timein=Carbon::parse($time_in);
        $timeout=Carbon::parse($time_out);
        // dd($timein);
        $work_time=$timeout->diffInSeconds($timein);
        $salary=($work_time/3600)*30000;
        // dd($salary);
        // dd($work_time);
        $attribute=[
            'user_id'=>$user,
            'work_time'=>$work_time,
            'salary'=>$salary,
        ];
        $work_sal=$this->salaryRepository->create($attribute);
        return $this->successRequest($work_sal->transform());

    }

    // public  function viewSalary(){
    //    $salarys=$this->salaryRepository->all();
    //    $data=[];
    //    foreach($salarys as $salary)
    //    {
    //        $data[]=$salary->transform();
    //    }
    //     return $this->successRequest($data);
    // }
}
