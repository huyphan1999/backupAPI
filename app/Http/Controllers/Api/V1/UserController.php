<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\User;

use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\ShopRepository;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthManager;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ShopRepository
     */
    protected $shopRepository;

    protected $request;

    protected $auth;

    public function __construct(
                                UserRepository $userRepository,
                                ShopRepository $shopRepository,
                                AuthManager $auth,
                                Request $request) {
        $this->userRepository = $userRepository;
        $this->shopRepository = $shopRepository;
        $this->request = $request;
        $this->auth = $auth;
        
        parent::__construct();
    }

    /**
     * @api {get} /user 1. Current user info
     * @apiDescription (current user info)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
                "error_code": 0,
                "message": [
                    "Successfully"
                ],
                "data": {
                    "id": "d8Wn2WmmjKnBtMRED",
                    "name": "Trung Hà",
                    "username": "+84909224002",
                    "email": "+84909224002@argi.com",
                    "phone": "+84909224002",
                    "phone_code": "84",
                    "is_supplier": 0,
                    "brandRepresent": "1",
                    "company": [
                        {
                            "name": "name",
                            "label": "Name",
                            "value": "GMA"
                        },
                    ]
                }
            }
     */
    public function userShow()
    {
        $user = $this->user();
        $data = $user->transform('with-shop');
        
        //Save history login
        $date = Carbon::now();
        $user->visited_date = $date;
        $user->vistied_ip = get_client_ip();
        $user->save();
        return $this->successRequest($data);
    }

    /**
     * @api {post}/user/update 2. update my info
     * @apiDescription Update my info
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} [name] name
     * @apiParam {String} [email] name
     * @apiParam {Object} [company] company[phone], company[address]...
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
                "error_code": 0,
                "message": [
                    "Successfully"
                ],
                "data": {
                    "id": "p5jFuwDbo84KteeCc",
                    "name": "Trung Hà",
                    "username": "+84909224002",
                    "phone": "+84909224002",
                    "phone_code": "84",
                    "company": {
                        "phone": "0909090909",
                        "address": "Bùi Hữu Nghĩa, Bình Thạnh"
                    },
                    "is_supplier": 0
                }
            }
     */
    public function update(Request $request)
    {
      // Send email when user register supplier
      // $params = ['email' => 'onclick.trungha@gmail.com',
      //            'full_name' => 'Trung Hà',
      //            'subject' => 'Đăng ký làm đại lý trên FAMA'];
      // var_dump($this->userRepository->sendMailActiveSupplier($params));return;

        $entityUser = new User;
        $fillableList = $entityUser->getFillable();

        $userId = $this->user->id;
        $user = $this->userRepository->findByField('_id',$userId)->first();
        
        foreach($fillableList as $key => $value){
            if($value == 'company'){
                if(!empty($this->request->get('company'))){
                  if(empty($user->company)){
                        $user->company = $this->request->get('company');
                    } else {
                        $company = $user->company;
                        foreach($this->request->get('company') as $k => $v){
                            $company[$k] = $v;
                        }
                        $user->company = $company;
                    }
                }
                
            } elseif ($value == 'email') {
                if(!empty($this->request->get('email'))) {
                    $emails = $user->emails;
                    $emails[0]['address'] = $this->request->get('email');
                    $user->emails = $emails;
                }
            }
            else {
                if(!empty($this->request->get($value)) || ($this->request->get($value) == 0 && $value != 'emails')){
                    $user->$value = $this->request->get($value);
                }
            }
        }
        $user->save();
        return $this->successRequest($user->transform());
    }

    /**
     * @api {GET} /user/info/{username} 3. User Info
     * @apiDescription Get user info
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} username  username's user
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "error_code": 0,
                "message": [
                    "Successfully"
                ],
                "data": [
                    {
                        "id": "oGpZf8tSv3FNLHZv4",
                        "name": "saritvn",
                        "username": "saritvn",
                        "phone": "0909224002",
                        "phone_code": "84",
                        "company": {
                            "name": "Green Mobile App",
                            "address": "195 Dien Bien Phu, Ward 15, Binh Thanh Distric, Ho Chi Minh City",
                            "email": "trung.ha@greenapp.vn",
                            "phone": "0909224002",
                            "field": "Mobile App"
                        }
                    }
                ]
     *     }
     */

    public function info(Request $request,$username){
        // Validate HEADER import.
        // $validator = \Validator::make($request->all(), [
        //     'username'   => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return $this->errorBadRequest($validator->messages()->toArray());
        // }

        $user = $this->userRepository->findByField('username',$username)->first();
        if(empty($user)){
            return $this->successRequest([]);
        }
        $data = $user->transform();
        return $this->successRequest($data);
    }
}
