<?php

namespace Gma\Payment\Provider;
use Gma\Payment\AbstractResponse;

class Response extends AbstractResponse
{
    public $message = '';
    public $other = '';
    /**
     * Set response Message of payment.
     *
     * @param  string  $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
    /**
     * Set response other info of payment of payment.
     *
     * @param  string  $other
     * @return $this
     */
    public function setOther($other)
    {
        $this->other = $other;

        return $this;
    }
}
