<?php


namespace App\Http\Controllers\Api\V1;
use App\Api\Entities\Shift;
use App\Api\Repositories\Contracts\EmpshiftRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\EmpClockRepository;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
use Firebase\Auth\Token\Exception\InvalidToken;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Yaml\Tests\B;

class EmpClockController extends Controller
{
    protected $empshiftRepository;

    protected  $userRepository;

    protected  $empclockRepository;

    protected $auth;

    protected $request;

    public function __construct(
        EmpshiftRepository $empshiftRepository,
        EmpClockRepository $empClockRepository,
        UserRepository $userRepository,
        AuthManager $auth,
        Request $request
    ) {
        $this->empshiftRepository = $empshiftRepository;
        $this->userRepository=$userRepository;
        $this->request = $request;
        $this->empclockRepository=$empClockRepository;
        $this->auth = $auth;
        parent::__construct();
    }
    public function TimeKeeping()
    {
        dd('asd');
        $user=$this->user();
        $shift_id=$this->request->get('shift_id');
        //Lấy thời gian lúc nhân viên bấm
        $time=Carbon::now;
        dd('asd');
        $shift=EmpShift::where(['shift_id'=>mongo_id($shift_id),'user_id'=>$user->_id]);
        if($shift->clicked==0){
            $attribute=[
                'time_in'=>$time,
                'time_out'=>null
            ];
        }
        if($shift->clicked==1)
        {
            $attribute=[
                'time_out'=>$time
            ];
        }
//        $emp_clock=$this->empclockRepository->create($attribute);
        return $this->successRequest($shift);
    }
}