<?php

namespace Gma\Payment\Provider\Gateway\Onepay;

use Gma\Payment\Provider\Gateway\BaseGateway;

class Card extends BaseGateway
{
    /**
     * @var 
     * @var $secret
     * @var $request
     * @var $returnUrl
     * @var $order_id
     * @var $order_amount
     * @var $order_description
     *                         {extend from parents}
     **/
    /**
     * @var Payment Name
     **/
    protected $gateway = 'card';
    public function __construct($config, $request)
    {
        parent::__construct($this->gateway, $config, $request);
        $this->returnUrl = $this->returnUrl.'onepay/card';
        $this->order_description = 'Thanh toán Gold trên BBOX.VN';
    }
    protected function execPostRequest($url, $data)
    {
        // open connection
        $ch = curl_init();
        // set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // execute post
        $result = curl_exec($ch);
        // close connection
        curl_close($ch);

        return $result;
    }
    public function url()
    {
        return false;
    }
    public function response()
    {
        $transRef = ''; //merchant's transaction reference
        //Telco viettel, mobifone, vinaphone, vnmobile
        $type = $this->request->get('telco');
        $pin = $this->request->get('code');
        $serial = $this->request->get('serial');
        $data = 'access_key='.$this->access_key.'&pin='.$pin.'&serial='.$serial.
                '&transRef='.$transRef.'&type='.$type;
        $signature = hash_hmac('sha256', $data, $this->secret);
        $data .= '&signature='.$signature;
        //do some thing
        $json_cardCharging = self::execPostRequest('https://api.1pay.vn/card-charging/v5/topup', $data);
        $decode_cardCharging = json_decode($json_cardCharging, true);  // decode json
        if (isset($decode_cardCharging)) {
            $description = $decode_cardCharging['description'];   // transaction description
            $status = $decode_cardCharging['status'];
            $amount = $decode_cardCharging['amount'];       // card's amount
            $transId = $decode_cardCharging['transId'];
            // xử lý dữ liệu của merchant
        } else {
            // run query API's endpoint
            $data_ep = 'access_key='.$this->access_key.'&pin='.$pin.'&serial='.$serial.'&transId=&transRef='.$transRef.'&type='.$type;
            $signature_ep = hash_hmac('sha256', $data_ep, $this->secret);
            $data_ep .= '&signature='.$signature_ep;
            $query_api_ep = self::execPostRequest('https://api.1pay.vn/card-charging/v5/query', $data_ep);
            $decode_cardCharging = json_decode($json_cardCharging, true);  // decode json
            $description_ep = $decode_cardCharging['description'];   // transaction description
            $status_ep = $decode_cardCharging['status'];
            $amount_ep = $decode_cardCharging['amount'];       // card's amount
            // Merchant handle SQL
        }
        $response = ['amount' => (int) $decode_cardCharging['amount'],
                     'more' => ['message' => $decode_cardCharging['description'],
                              'other' => 'telco: '.$this->request->get('telco').
                                         ' - serial: '.$this->request->get('serial').
                                         ' - code: '.$this->request->get('code'),
                             ],
                     'transaction' => $decode_cardCharging['transRef'],
                     'orderId' => $this->request->get('order_id'),
                    ];

        return $response;
    }
}
