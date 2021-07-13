<?php


namespace App\Repositories\Eloquent;


use App\Models\Design;
use App\Repositories\Contracts\IDesign;
use Illuminate\Support\Collection;

class DesignRepository implements IDesign
{
    public function all(): Collection
    {
        return Design::all();
    }
}
