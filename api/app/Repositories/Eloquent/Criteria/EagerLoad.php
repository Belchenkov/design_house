<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Criteria\ICriterion;
use Illuminate\Database\Eloquent\Builder;

class EagerLoad implements ICriterion
{
    protected array $relationships;

    public function __construct(array $relationships)
    {
        $this->relationships = $relationships;
    }

    public function apply($model): Builder
    {
        return $model->with($this->relationships);
    }
}
