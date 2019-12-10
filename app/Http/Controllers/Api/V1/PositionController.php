<?php


namespace App\Http\Controllers\Api\V1;

use App\Api\Repositories\Contracts\PositionRepository;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;
use Gma\Curl;
use App\Api\Entities\Position;
//Google firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Yaml\Tests\B;

class PositionController extends Controller
{
    protected $positionRepository;
    protected $request;
    public function __construct(Request $request,PositionRepository $positionRepository)
    {
        $this->request = $request;
        $this->positionRepository = $positionRepository;
        parent::__construct();
    }
    public function createPosition()
    {
        $validator= $validator = \Validator::make($this->request->all(), [
            'position' => 'required',
            'permission'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages()->toArray());
        }
        $attribute=[
            'position'=>$this->request->get('position'),
            'permission'=>$this->request->get('permission'),
        ];
        $position=$this->positionRepository->create($attribute);
        $data=$position->transform();
        return $this->successRequest($data);
    }


}