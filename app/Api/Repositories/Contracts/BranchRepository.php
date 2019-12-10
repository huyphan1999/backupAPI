<?php

namespace App\Api\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BranchRepository
 */
interface BranchRepository extends RepositoryInterface
{
    public function getBranch($params = [],$limit = 0);
}
