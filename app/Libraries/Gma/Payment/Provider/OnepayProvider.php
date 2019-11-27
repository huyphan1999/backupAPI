<?php

namespace Gma\Payment\Provider;

use Illuminate\Support\Arr;
use GuzzleHttp\ClientInterface;
class OnePayProvider extends AbstractProvider
{

    protected $_onepayPath = 'Gma\Payment\Provider\Gateway\Onepay\\';
    //protected $config;
    public function url()
    {
        $gateway = $this->_onepayPath.ucfirst($this->payType);
        $objGateway = new $gateway($this->config,$this->request);
        $url = $objGateway->url();
        return $url;
    }
    public function getPayment(){
        $gateway = $this->_onepayPath.ucfirst($this->payType);
        $objGateway = new $gateway($this->config,$this->request);
        $response = $objGateway->response();
        return $response;
    }
    /**
     * {@inheritdoc}
     */
    protected function mapResponseToObject(array $response)
    {
        return (new Response)->setRaw($response)->map([
            'orderId' => $response['orderId'], 
            'amount' => $response['amount'], 
            'transaction' => $response['transaction']
        ]);
    }
}
