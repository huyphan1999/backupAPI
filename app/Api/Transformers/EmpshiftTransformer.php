<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Empshift;
use Carbon\Carbon;

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
            'user_id'=>$model->user_id,
            'shift_id'=>$model->shift_id,
        ];
    }
}
