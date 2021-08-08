<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    protected IUser $users;

    /**
     * @param IUser $users
     */
    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();

        return UserResource::collection($users);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $designers = $this->users->search($request);
        return UserResource::collection($designers);
    }

    /**
     * @param string $username
     * @return UserResource
     */
    public function findByUserName(string $username): UserResource
    {
        $user = $this->users->findWhereFirst('username', $username);
        return new UserResource($user);
    }
}
