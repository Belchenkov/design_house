<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Criteria\ICriterion;
use Illuminate\Database\Eloquent\Builder;

class IsLive implements ICriterion
{
    public function apply($model): Builder
    {
        return $model->where('is_live', true);
    }
}
