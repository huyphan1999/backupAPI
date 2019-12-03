<?php

namespace App\Api\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;
use App\Api\Entities\Shop;

/**
 * Interface ShopRepository
 */
interface ShopRepository extends RepositoryInterface
{
    public function getShop($params = [],$limit = 0);
}
