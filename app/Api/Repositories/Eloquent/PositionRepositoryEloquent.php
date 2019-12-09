<?php

namespace App\Api\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\PositionRepository;
use App\Api\Entities\Position;
use App\Api\Validators\PositionValidator;

/**
 * Class PositionRepositoryEloquent
 */
class PositionRepositoryEloquent extends BaseRepository implements PositionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Position::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }
}
