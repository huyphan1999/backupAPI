<?php

namespace Gma\Payment\Contracts;

interface Provider
{
    /**
     * Redirect the user to the authentication page for the provider.
     *
     * @return \Gma\Payment\Provider\ProviderInterface
     */
    public function url();

    /**
     * Get the User instance for the authenticated user.
     *
     * @return Gma\Payment\Contracts\Responses
     */
    public function response();
    /**
    * Set pay Type for each Gateway.
    * Gma\Payment\Provider\AbstractProvider
    **/
    public function payType($payType);
}
