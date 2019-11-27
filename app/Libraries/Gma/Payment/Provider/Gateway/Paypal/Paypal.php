<?php

namespace Gma\Payment\Provider\Gateway\Paypal;

use Gma\Payment\Provider\Gateway\BaseGateway;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class Paypal extends BaseGateway
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
    protected $gateway = 'paypal';

    /**
    * @param _api_context
    **/
    protected $_api_context;
    public function __construct($config, $request)
    {
        parent::__construct($this->gateway, $config, $request);
        $this->returnUrl = $this->returnUrl.'paypal/paypal?'.$this->order_id.'&order_id='.$this->order_id;
        $this->order_description = 'Thanh toán Gold trên BBOX.VN';

        //Set paypal context
        $this->_api_context = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->_api_context->setConfig($this->settings);
    }
    public function url()
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array());
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($this->order_amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($this->order_description);
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($this->returnUrl)
            ->setCancelUrl(ROOT_URL);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }
        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        echo $redirect_url;return;
    }
    public function response()
    {

        $payment_id = $this->request->get('paymentId');
        $payment = Payment::get($payment_id, $this->_api_context);

        // to execute a PayPal account payment. 
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($this->request->get('PayerID'));
        
        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') { // payment made
            $transactions = $result->transactions;
            $amount = $transactions[0]->amount->total
            return ['amount' => $amount,
                    'orderId' => $this->request->get('order_id'),
                    'transaction' => $payment_id,
                    'more' => ['message' => "Thanh toán thành công"],
                    ];
        }else{
            return ['amount' => 0,
                    'orderId' => $this->request->get('order_id'),
                    'transaction' => '',
                    'more' => ['message' => "Thanh toán thất bại"],
                    ];
        }
    }
}
