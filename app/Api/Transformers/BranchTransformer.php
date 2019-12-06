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
            'branchName' => $model->branchName,
            'address' => $model->address,
        ];
        return $data;
    }
}
