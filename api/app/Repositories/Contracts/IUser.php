<?php


namespace App\Repositories\Contracts;


use Illuminate\Support\Collection;

interface IUser
{
    public function all(): Collection;
}
