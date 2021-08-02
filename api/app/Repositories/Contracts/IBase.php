<?php


namespace App\Repositories\Contracts;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface IBase
{
    public function all(): Collection;
    public function find(int $id): Model;
    public function findWhere($column, $value): Collection;
    public function findWhereFirst($column, $value): Model;
    public function paginate(int $per_page = 10): Collection;
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);

}
