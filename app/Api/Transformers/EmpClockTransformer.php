<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\EmpClock;

/**
 * Class EmpClockTransformer
 */
class EmpClockTransformer extends TransformerAbstract
{

    /**
     * Transform the \EmpClock entity
     * @param \EmpClock $model
     *
     * @return array
     */
    public function transform(EmpClock $model)
    {
        return [
            'id'         => $model->_id,

            

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
