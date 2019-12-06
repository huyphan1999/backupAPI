<?php

namespace App\Api\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\BranchRepository;
use App\Api\Entities\Branch;
use App\Api\Validators\BranchValidator;

/**
 * Class BranchRepositoryEloquent
 */
class BranchRepositoryEloquent extends BaseRepository implements BranchRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Branch::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }
}
