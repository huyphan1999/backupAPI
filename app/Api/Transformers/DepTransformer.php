<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Dep;

/**
 * Class BranchTransformer
 */
class DepTransformer extends TransformerAbstract
{

    /**
     * Transform the \Branch entity
     * @param \Branch $model
     *
     * @return array
     */
    public function transform(Dep $model, string $type = ''){
        $data = [
            'id'=>$model->_id,
            'dep_name' => $model->dep_name,
            'branch'=>[],
        ];
        $branch=$model->branch();
        if(!empty($branch)) {
            $data['branch'] = $branch->transform();
        }
        return $data;
    }
}
