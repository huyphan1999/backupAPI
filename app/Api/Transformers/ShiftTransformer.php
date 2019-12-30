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
        $time_in=$model->time_begin;
        $time_out=$model->time_end;
        $time=$time_in.'-'.$time_out;
        return [
            'date'=> $model->work_date,
            'data'=>[
                'name'=>$model->shift_name,
                'time'=>$time,
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
