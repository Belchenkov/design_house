<?php


namespace App\Repositories\Eloquent;


use App\Models\Design;
use App\Repositories\Contracts\IDesign;

class DesignRepository extends BaseRepository implements IDesign
{
    public function model(): string
    {
        return Design::class;
    }

    public function applyTags(int $id, array $data): void
    {
        $design = $this->find($id);
        $design->retag($data);
    }

}
