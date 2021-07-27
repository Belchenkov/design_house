<?php


namespace App\Repositories\Contracts;


use App\Models\User;

interface IUser
{
    public function findByEmail(string $email): User;
}
