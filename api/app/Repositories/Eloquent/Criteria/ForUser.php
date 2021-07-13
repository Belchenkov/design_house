<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Criteria\ICriterion;
use Illuminate\Database\Eloquent\Builder;

class ForUser implements ICriterion
{
    protected string $user_id;

    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }

    public function apply($model): Builder
    {
        return $model->where('user_id', $this->user_id);
    }
}
