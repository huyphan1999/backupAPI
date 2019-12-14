<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\User2;

/**
 * Class User2Transformer
 */
class User2Transformer extends TransformerAbstract
{

    /**
     * Transform the \User2 entity
     * @param \User2 $model
     *
     * @return array
     */
    public function transform(User2 $model, $type = '')
    {
        $data = array(
            'id' => $model->_id,
            'full_name'=>$model->full_name,
            'name' => $model->name,
            'email' => $model->email,
            'is_root' => (int)$model->is_root,
            'shop_id' => mongo_id_string($model->shop_id),
            'shop' => [],
        );
        $shop = $model->shop();
        if(!empty($shop)) {
            $data['shop'] = $shop->transform();
        }
        return $data;
    }
}
