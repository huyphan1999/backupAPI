<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Test;

/**
 * Class TestTransformer
 */
class TestTransformer extends TransformerAbstract
{

    /**
     * Transform the \Test entity
     * @param \Test $model
     *
     * @return array
     */
    public function transform(Test $model)
    {
        return [
            'id'         => $model->_id,

            

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
