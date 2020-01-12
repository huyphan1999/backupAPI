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
        $shift=$model->shift();
        if(!empty($shift))
        {
           return $shift;
        }
    }
}
