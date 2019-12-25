<?php

namespace App\Api\Entities;

use Moloquent\Eloquent\Model as Moloquent;
use App\Api\Transformers\EmpClockTransformer;
use Moloquent\Eloquent\SoftDeletes;

class EmpClock extends Moloquent
{
	use SoftDeletes;

	protected $collection = '';

    protected $fillable = [];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transform()
    {
        $transformer = new EmpClockTransformer();

        return $transformer->transform($this);
    }

}
