<?php

namespace Gma\Payment;

use InvalidArgumentException;
use Illuminate\Support\Manager;

class PaymentManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return Gma\Payment\Provider\AbstractProvider
     */
    protected function createNganluongDriver()
    {
        $config = $this->app['config']['services.nganluong'];

        return $this->buildProvider(
            'Gma\Payment\Provider\NganluongProvider', $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return Gma\Payment\Provider\AbstractProvider
     */
    protected function createOnepayDriver()
    {
        $config = $this->app['config']['services.onepay'];

        return $this->buildProvider(
            'Gma\Payment\Provider\OnepayProvider', $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return Gma\Payment\Provider\AbstractProvider
     */
    protected function createPaypalDriver()
    {
        $config['paypal'] = $this->app['config']['paypal'];

        return $this->buildProvider(
            'Gma\Payment\Provider\PaypalProvider', $config
        );
    }


    /**
     * Build an OAuth 2 provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return Gma\Payment\Provider\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider($this->app['request'], $config);
    }
    /**
     * Get the default driver name.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Payment driver was specified.');
    }

}
