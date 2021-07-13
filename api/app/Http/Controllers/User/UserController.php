<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected IUser $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        return UserResource::collection($this->users->all());
    }
}
