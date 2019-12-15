<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Shift;

/**
 * Class ShiftTransformer
 */
class ShiftTransformer extends TransformerAbstract
{

    /**
     * Transform the \Shift entity
     * @param \Shift $model
     *
     * @return array
     */
    public function transform(Shift $model)
    {
        return [
            'dep_id'=>$model->dep_id,
            'shift_name'         => $model->shift_name,
            'time_begin'=>$model->time_begin,
            'time_out'=>$model->time_end
        ];
    }
}
