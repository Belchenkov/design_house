<?php


namespace App\Repositories\Eloquent;


use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository implements IBase, ICriteria
{
    protected $model;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function all(): Collection
    {
        return $this->model->get();
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function findWhere($column, $value): Collection
    {
        return $this->model->where($column, $value)->get();
    }

    public function findWhereFirst($column, $value): Model
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    public function paginate(int $per_page = 10): Collection
    {
        return $this->model->paginate($per_page);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete(int $id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    public function withCriteria(...$criteria): self
    {
        $criteria = Arr::flatten($criteria);

        foreach ($criteria as $criterion) {
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }

    protected function getModelClass(): Model
    {
        if (! method_exists($this, 'model')) {
            throw new ModelNotDefined();
        }

        return app()->make($this->model());
    }
}
