<?php

namespace App\Api\Entities;

use Moloquent\Eloquent\Model as Moloquent;
use App\Api\Transformers\PositionTransformer;
use Moloquent\Eloquent\SoftDeletes;

class Position extends Moloquent
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
        $transformer = new PositionTransformer();

        return $transformer->transform($this);
    }

}
