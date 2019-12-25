<?php

namespace App\Api\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\EmpClockRepository;
use App\Api\Entities\EmpClock;
use App\Api\Validators\EmpClockValidator;

/**
 * Class EmpClockRepositoryEloquent
 */
class EmpClockRepositoryEloquent extends BaseRepository implements EmpClockRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EmpClock::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }
}
