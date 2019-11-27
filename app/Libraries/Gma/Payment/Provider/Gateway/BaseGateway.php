<?php
namespace Gma\Payment\Provider\Gateway;

use App\Api\Entities\PaymentOrder;
class BaseGateway
{
    /**
    * @var $order_id
    **/
    protected $order_id;
    
    /**
    * @var Description of payment.
    **/
    /**
    * @var $order_id Payment Order 
    **/
    protected $order_amount;
    /**
    * @var $order_description Detail of payment
    **/
    protected $order_description;
    /**
    * @var Gateway access key $access_key
    **/
    protected $access_key;
    /**
    * @var Gateway secret $secret
    **/
    protected $secret;

    /**
    * @var Gateway client_id $client_id
    **/
    protected $client_id;

    /**
    * @var Gateway client_setting $client_setting
    **/
    protected $settings;

    /**
    * @var Gateway request $request
    **/
    protected $request;
    /**
    * @var Gateway return URL of gateway $returnUrl
    **/
    protected $returnUrl = '';
    /**
    * @var $objOrder collection Order
    **/
    protected $objOrder;

    public function __construct($gateway,$config,$request)
    {
        if(!empty($config[$gateway]['access_key'])){
            $this->access_key = $config[$gateway]['access_key'];
        }

        if(!empty($config[$gateway]['secret'])){
            $this->secret = $config[$gateway]['secret'];
        }

        if(!empty($config[$gateway]['client_id'])){
            $this->client_id = $config[$gateway]['client_id'];
        }

        if(!empty($config[$gateway]['settings'])){
            $this->settings = $config[$gateway]['settings'];
        }
        
        $this->request = $request;
        $this->returnUrl = API_URL.'payment/callback/';

        //Get Order ID from Request.
        $this->order_id = $request->get('order_id');
        //If don't have order_id from request, get it from route.
        if(empty($this->order_id)){
            $this->order_id = $request->route()[2]['order_id'];
        }
        //Get collection Order.
        if(!empty($this->order_id)){
            $this->objOrder = $this->getOrderInfo($this->order_id);
        }
        //Set some Info Payment
        if(!empty($this->objOrder)){
            $this->order_amount = $this->objOrder['amount'];
        }

    }
    /**
    * @decription Get Order Info
    * @param $orderId
    * @return array order info.
    **/
    public function getOrderInfo($orderId){
        if(empty($orderId)){
            return false;
        }
        $order = PaymentOrder::find($orderId);
        if(!empty($order)){
            return $order->transform();
        }else{
            return false;
        }
    }
}
