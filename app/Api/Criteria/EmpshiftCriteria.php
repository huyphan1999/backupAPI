<?php

namespace App\Api\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Auth;
/**
 * Class EmpshiftCriteria
 */
class EmpshiftCriteria implements CriteriaInterface
{
    protected $params;
    public function __construct($params = [])
    {
        $this->params = $params;
    }
    
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->newQuery();
        if(!empty($this->params['date_checkin'])) {
            $query->where('date_checkin',$this->params['date_checkin']);
        }
        //Set language
        // $query->where('lang',app('translator')->getLocale());




        $query->orderBy('sort_index','asc');
        $query->orderBy('updated_at', 'asc');


        return $query;
    }
}
