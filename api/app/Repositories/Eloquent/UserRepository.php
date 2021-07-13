<?php


namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Contracts\IUser;
use Illuminate\Support\Collection;

class UserRepository implements IUser
{
    public function all(): Collection
    {
        return User::all();
    }
}
