<?php

namespace Gma\Payment\Provider;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Gma\Payment\Contracts\Provider as ProviderContract;

abstract class AbstractProvider implements ProviderContract
{
    /**
     * The HTTP request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * The client ID.
     *
     * @var string
     */
    protected $config;

    /**
     * The custom parameters to be sent with the request.
     *
     * @var array
     */
    protected $parameters = [];

    protected $_payType;

    /**
     * Create a new provider instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $config
     * @return void
     */
    public function __construct(Request $request, $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * Get Payment Info
     * @return Gma\Payment\Provider\Response
     */
    abstract protected function getPayment();

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return Gma\Payment\Provider\Response
     */
    abstract protected function mapResponseToObject(array $payment);

    /**
     * {@inheritdoc}
     */

    public function url(){}

    /**
     * {@inheritdoc}
     */
    public function response()
    {
        $payment = $this->getPayment();
        if(empty($payment)){
            return false;
        }else{
            //Get more info for set some atribute and then unset it.
            if(!empty($payment['more'])){
                $more = $payment['more'];
                unset($payment['more']);
            }
            $respone = $this->mapResponseToObject($payment);
            if(!empty($more['message'])){
                $respone->setMessage($more['message']);
            }
            if(!empty($more['other'])){
                $respone->setOther($more['other']);
            }
            
            return $respone;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function payType($payType){
        $this->payType = $payType;
    }

     /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }

    /**
     * Set the Guzzle HTTP client instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return $this
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Set the request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
    
    /**
     * Set the custom parameters of the request.
     *
     * @param  array  $parameters
     * @return $this
     */
    public function with(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
