<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Empshift;

/**
 * Class EmpshiftTransformer
 */
class EmpshiftTransformer extends TransformerAbstract
{

    /**
     * Transform the \Empshift entity
     * @param \Empshift $model
     *
     * @return array
     */
    public function transform(Empshift $model)
    {
        return [
            'date_checkin'=>$model->date_checkin,
            'time_in'=>$model->time_in,
            'time_out'=>$model->time_out,
        ];
    }
}
