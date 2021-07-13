<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Criteria\ICriterion;
use Illuminate\Database\Eloquent\Builder;

class LatestFirst implements ICriterion
{
    public function apply($model): Builder
    {
        return $model->latest();
    }
}
