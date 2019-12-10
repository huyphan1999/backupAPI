<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Position;

/**
 * Class PositionTransformer
 */
class PositionTransformer extends TransformerAbstract
{

    /**
     * Transform the \Position entity
     * @param \Position $model
     *
     * @return array
     */
    public function transform(Position $model)
    {
        return [
            'id'         => $model->_id,
            'position'  =>$model->position,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
