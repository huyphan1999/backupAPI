<?php

namespace Gma\Payment\Provider\Gateway\Onepay;

use Gma\Payment\Provider\Gateway\BaseGateway;

class Banking extends BaseGateway
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
    protected $gateway = 'banking';

    public function __construct($config, $request)
    {
        parent::__construct($this->gateway, $config, $request);
        $this->returnUrl = $this->returnUrl.'onepay/banking';
        $this->order_description = 'Thanh toán Gold trên BBOX.VN';
    }
    protected function execPostRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    public function url()
    {
        $command = 'request_transaction';
        $amount = $this->order_amount;   // >10000
        $order_id = $this->order_id;
        $order_info = $this->order_description;
        $data = 'access_key='.$this->access_key.'&amount='.$amount.'&command='.$command.'&order_id='.$order_id.'&order_info='.$order_info.'&return_url='.$this->returnUrl;
        $signature = hash_hmac('sha256', $data, $this->secret);
        $data .= '&signature='.$signature;
        $json_bankCharging = $this->execPostRequest('http://api.1pay.vn/bank-charging/service', $data);
        $decode_bankCharging = json_decode($json_bankCharging, true);  // decode json
        $pay_url = $decode_bankCharging['pay_url'];

        return $pay_url;
    }
    public function response()
    {
        if (!empty($this->request->get('trans_ref'))) {
            $trans_ref = $this->request->get('trans_ref');
        } else {
            $trans_ref = null;
        }

        if (!empty($this->request->get('response_code'))) {
            $response_code = $this->request->get('response_code');
        } else {
            $response_code = null;
        }

        if ($response_code == '00') {
            $command = 'close_transaction';

            $data = 'access_key='.$this->access_key.'&command='.$command.'&trans_ref='.$trans_ref;
            $signature = hash_hmac('sha256', $data, $this->secret);
            $data .= '&signature='.$signature;

            $json_bankCharging = self::execPostRequest('http://api.1pay.vn/bank-charging/service', $data);

            $decode_bankCharging = json_decode($json_bankCharging, true);  // decode json
            // Ex: {"amount":10000,"trans_status":"close","response_time": "2014-12-31T00:52:12Z","response_message":"Giao dịch thành công","response_code":"00","order_info":"test dich vu","order_id":"001","trans_ref":"44df289349c74a7d9690ad27ed217094", "request_time":"2014-12-31T00:50:11Z","order_type":"ND"}
            $response_message = $decode_bankCharging['response_message'];
            $response_code = $decode_bankCharging['response_code'];
            $amount = $decode_bankCharging['amount'];
            //var_dump($decode_bankCharging);
            if ($response_code == '00') {
                return ['amount' => $decode_bankCharging['amount'],
                    'orderId' => $decode_bankCharging['order_id'],
                    'transaction' => $decode_bankCharging['trans_ref'],
                    'more' => ['message' => $decode_bankCharging['response_message']],
                    ];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
