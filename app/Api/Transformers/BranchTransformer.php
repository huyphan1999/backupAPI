<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Branch;

/**
 * Class BranchTransformer
 */
class BranchTransformer extends TransformerAbstract
{

    /**
     * Transform the \Branch entity
     * @param \Branch $model
     *
     * @return array
     */
    public function transform(Branch $model, string $type = ''){
        $data = [
            'branch_name' => $model->branch_name,
            'address' => $model->address,
        ];
        return $data;
    }
}
