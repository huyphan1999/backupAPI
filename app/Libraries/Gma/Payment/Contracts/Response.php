<?php

namespace Gma\Payment\Contracts;

interface Response
{
    /**
     * Get the orderID.
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Get the Amount of order
     *
     * @return string
     */
    public function getAmount();

    /**
     * Get the transation of order.
     *
     * @return string
     */
    public function getTransaction();
}
