<?php

namespace Gma\Payment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see Gma\Payment\PaymentManager
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Gma\Payment\Contracts\Factory';
    }
}
