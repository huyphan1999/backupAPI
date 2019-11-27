<?php

namespace Gma\Payment\Contracts;

interface Factory
{
    /**
     * Get an Payment provider implementation.
     *
     * @param string $driver
     *
     * @return App\Libraries\Gma\Payment\Contracts\Provider
     */
    public function driver($driver = null);
}
