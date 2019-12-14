<?php

namespace App\Api\Repositories\Eloquent;

use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\PositionRepository;
use App\Api\Repositories\Contracts\BranchRepository;
use App\Api\Repositories\Contracts\DepRepository;
use App\Api\Repositories\Contracts\ShopRepository;
use App\Api\Entities\Shop;
use App\Api\Entities\User;
use App\Api\Validators\ShopValidator;
use App\Api\Criteria\ShopCriteria;
use Illuminate\Http\Request;
use App\Api\Entities\Role;
use Carbon\Carbon;
/**
 * Class ShopRepositoryEloquent
 */
class ShopRepositoryEloquent extends BaseRepository implements ShopRepository
{
    protected $userRepository;
    protected $depRepository;
    protected $branchRepository;
    protected $positionRepository;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Shop::class;
    }

    public function __construct(Application $app,UserRepository $userRepository,
                                PositionRepository $positionRepository,
                                DepRepository $depRepository,
                                BranchRepository $branchRepository)
    {
        $this->userRepository=$userRepository;
        $this->depRepository=$depRepository;
        $this->branchRepository=$branchRepository;
        $this->positionRepository=$positionRepository;
        parent::__construct($app);
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
    public function deleteShop($id,$limit =0)
    {
            if(is_array($id))
            {
                foreach($id as $row)
                {
                    $user=$this->userRepository->deleteWhere(['shop_id'=>mongo_id($row->id)]);
                    $position=$this->positionRepository->deleteWhere(['shop_id'=>mongo_id($row->id)]);
                    $dep=$this->depRepository->deleteWhere(['shop_id'=>mongo_id($row->id)]);
                    $branch=$this->branchRepository->deleteWhere(['shop_id'=>mongo_id($row->id)]);
                    $shop=$this->shopRepository->deleteWhere(['_id'=>mongo_id($row->id)]);
                }

            }
            else
            {
                $user=$this->userRepository->deleteWhere(['shop_id'=>mongo_id($id)]);
                $position=$this->positionRepository->deleteWhere(['shop_id'=>mongo_id($id)]);
                $dep=$this->depRepository->deleteWhere(['shop_id'=>mongo_id($id)]);
                $branch=$this->branchRepository->deleteWhere(['shop_id'=>mongo_id($id)]);
                $shop=$this->shopRepository->deleteWhere(['_id'=>mongo_id($id)]);
            }
            return $this->successRequest("Đã xóa thành công");
    }
}
