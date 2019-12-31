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
    // public function transform(EmpClock $model)
    // {
    //     $data= [
    //         'user_id'=>$model->user_id,
    //         'shift'=>[

    //         ],
    //         'time_in'=>$model->time_in,
    //         'time_out'=>$model->time_out,
    //     ];
    //     // $user=$model->user();
    //     // if(!empty($user))
    //     // {
    //     //     $data['user']=$user->transform();
    //     // }
    //     $shift=$model->shift();
    //     if(!empty($shift))
    //     {
    //         $data['shift']=$shift->transform();
    //     }
    //     return $data;
    // }
    public function transform(EmpClock $model)
    {
<<<<<<< HEAD
        $shift=$model->shift_id;
               
        
        $data= [
            'data'=>[],
            'status'=>$model->status
        ];

        $shift=$model->shift();
        if(!empty($shift))
        {
            $data['data']=$shift->transform();
        }
        return $data;

=======
        $data= [
            'user_id'=>$model->user_id,
            'shift'=>[

            ],
            'time_in'=>$model->time_in,
            'time_out'=>$model->time_out,
        ];
        // $user=$model->user();
        // if(!empty($user))
        // {
        //     $data['user']=$user->transform();
        // }
        $shift=$model->shift();
        if(!empty($shift))
        {
            $data['shift']=$shift->transform();
        }
        return $data;
>>>>>>> 67ff75c577e71488faeaca240ea2a11a83b7b8e1
    }
}
