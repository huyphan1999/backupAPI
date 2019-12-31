<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Salary;

/**
 * Class SalaryTransformer
 */
class SalaryTransformer extends TransformerAbstract
{

    /**
     * Transform the \Salary entity
     * @param \Salary $model
     *
     * @return array
     */
    public function transform(Salary $model)
    {
<<<<<<< HEAD
        $id=$model->user_id;
        $data = [
            'work_time'=>$model->work_time,
            'salary'=>$model->salary,
        ];
        return[
            'user_id'=> $id,
=======
        return [
            'user_id'=> $model->user_id,
>>>>>>> 67ff75c577e71488faeaca240ea2a11a83b7b8e1
            'work_time'=>$model->work_time,
            'salary'=>$model->salary,
        ];
    }
}
