<?php


namespace App\Repositories\Contracts;


use Illuminate\Support\Collection;

interface IDesign
{
    public function all(): Collection;
}
