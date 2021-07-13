<?php


namespace App\Repositories\Eloquent;


use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository implements IBase
{
    protected Model $model;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function all(): Collection
    {
        return $this->model::all();
    }

    protected function getModelClass(): Model
    {
        if (! method_exists($this, 'model')) {
            throw new ModelNotDefined();
        }

        return app()->make($this->model());
    }
}
