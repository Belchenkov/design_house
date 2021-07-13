<?php


namespace App\Repositories\Contracts;


use Illuminate\Support\Collection;

interface IBase
{
    public function all(): Collection;
}
