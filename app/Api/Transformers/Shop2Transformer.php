<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Shop2;

/**
 * Class Shop2Transformer
 */
class Shop2Transformer extends TransformerAbstract
{

    /**
     * Transform the \Shop2 entity
     * @param \Shop2 $model
     *
     * @return array
     */
    public function transform(Shop2 $model, $type = ''){
        $data = [
            'id' => $model->_id,
            'shop_name'=>$model->shop_name,
            'name' => $model->name,
        ];
        return $data;
    }
}
