<?php

namespace App\Api\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\ShopRepository;
use App\Api\Entities\Shop;
use App\Api\Validators\ShopValidator;
use App\Api\Criteria\ShopCriteria;

use App\Api\Entities\PaymentOrder;
use App\Api\Entities\Role;
use Carbon\Carbon;
/**
 * Class ShopRepositoryEloquent
 */
class ShopRepositoryEloquent extends BaseRepository implements ShopRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Shop::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }
    /**
    * Get list Shop
    **/
    public function getShop($params = [],$limit = 0) {
        $this->pushCriteria(new ShopCriteria($params));
        if(!empty($params['is_detail'])) {
            $item = $this->get()->first();
        } elseif(!empty($params['is_paginate'])) {
            $item = $this->paginate();  
        } else {
            $item = $this->all(); 
        }
        $this->popCriteria(new ShopCriteria($params));
        return $item;
    }

    /**
    * Make Plan for shop.
    *
    **/
    public function makePackage(Shop $shop, PaymentOrder $order) {
        $start_package = Carbon::parse(Carbon::now()->toDateString());
        $end_package = (Clone $start_package)->addMonths($order->month);
        $arrOrder = $order->toArray();
        $package = $arrOrder['package'];
        $package['order_id'] = mongo_id($order->_id);
        $shop->start_package = $start_package;
        $shop->end_package = $end_package;
        $shop->package = $package;

        // Set thêm thông tin thanh toán
        if(!empty($order->billing_info)) {
            $shop->billing_info = $order->billing_info;
        }
        $shop->save();
        return true;
    }

}
