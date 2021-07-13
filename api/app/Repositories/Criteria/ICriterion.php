<?php


namespace App\Repositories\Criteria;


use Illuminate\Database\Eloquent\Builder;

interface ICriterion
{
    public function apply($model): Builder;
}
