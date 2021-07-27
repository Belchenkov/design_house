<?php


namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Contracts\IUser;

class UserRepository extends BaseRepository implements IUser
{
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email): User
    {
        return $this->model->where('email', $email);
    }
}
