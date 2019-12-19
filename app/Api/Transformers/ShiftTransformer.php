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
            'date'=> $model->work_date,
            'data'=>[
                'name'=>$model->shift_name,
                'time'=>$model->time,
            ],
        ];


    }
    /*public function transform(Shift $model)
    {
        return [
                'name'=>$model->shift_name,
                'time'=>$model->time,
        ];


    }*/



}
