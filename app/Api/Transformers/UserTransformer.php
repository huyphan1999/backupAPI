<?php

namespace App\Api\Transformers;

use App\Api\Entities\User;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use League\Fractal\TransformerAbstract;
use App\Api\Entities\Organization;
use App\Api\Entities\Element;
use App\Api\Entities\UserCountry;
use App\Api\Entities\UserProvince;
use App\Api\Entities\UserTown;

use Carbon\Carbon;
use InvalidArgumentException;
/**
 * Class UserTransformer.
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * @var request
     */
    protected $request;

    public function __construct()
    {
        $request = App::make(Request::class);
    }

    /**
     * Transform the \User entity.
     *
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model, $type = '')
    {

        $data = array(
            'id' => $model->_id,
            'name' => $model->name,
            'email' => $model->email,
            'is_root' => (int)$model->is_root,
            'shop_id' => mongo_id_string($model->shop_id),
            'position_id'=>mongo_id_string($model->position_id),
            'shop' => [],
            'position'=>[],
        );
        $shop = $model->shop();
        $position= $model->position();
        if(!empty($shop)) {
            $data['shop'] = $shop->transform();
        }
        if(!empty($shop)) {
            $data['position'] = $position->transform();
        }
        return $data;
    }
}
