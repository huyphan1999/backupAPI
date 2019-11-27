<?php

namespace Gma\Payment;

use ArrayAccess;

abstract class AbstractResponse implements ArrayAccess, Contracts\Response
{
    /**
     * The unique order ID
     *
     * @var mixed
     */
    public $orderId;

    /**
     * The amount of payment
     *
     * @var string
     */
    public $amount;

    /**
     * The transation for this order
     *
     * @var string
     */
    public $transaction;

    /**
     * The resonse's raw attributes.
     *
     * @var array
     */
    public $response;

    /**
     * Get the order Id
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Get amount of order
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the transaction of order
     *
     * @return string
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Get the raw response array.
     *
     * @return array
     */
    public function getRaw()
    {
        return $this->response;
    }

    /**
     * Set the raw respone array from the provider.
     *
     * @param  array  $response
     * @return $this
     */
    public function setRaw(array $response)
    {
        $this->response = $response;
        
        return $this;
    }

    /**
     * Map the given array onto the response's properties.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Determine if the given raw response attribute exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->response);
    }

    /**
     * Get the given key from the raw resonse.
     *
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->response[$offset];
    }

    /**
     * Set the given attribute on the raw response array.
     *
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->response[$offset] = $value;
    }

    /**
     * Unset the given value from the raw response array.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->response[$offset]);
    }
}
