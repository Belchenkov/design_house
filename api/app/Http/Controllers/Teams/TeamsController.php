<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\IInvitation;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TeamsController extends Controller
{
    protected ITeam $teams;
    protected IUser $users;
    protected IInvitation $invitations;

    public function __construct(ITeam $teams, IUser $users, IInvitation $invitations)
    {
        $this->teams = $teams;
        $this->users = $users;
        $this->invitations = $invitations;
    }

    /**
     * Get List of all teams (eg for Search)
     */
    public function index()
    {

    }

    /**
     * Save team to DB
     * @param Request $request
     * @return TeamResource
     * @throws ValidationException
     */
    public function store(Request $request): TeamResource
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name']
        ]);

        $team = $this->teams->create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    /**
     * Update team information
     * @param Request $request
     * @param int $id
     * @return TeamResource
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function update(Request $request, int $id): TeamResource
    {
        $team = $this->teams->find($id);
        $this->authorize('update', $team);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name,' . $id]
        ]);

        $team = $this->teams->update($id, [
           'name' => $request->name,
           'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    /**
     * Find a team by its ID
     * @param int $id
     * @return TeamResource
     */
    public function findById(int $id): TeamResource
    {
        $team = $this->teams->find($id);
        return new TeamResource($team);
    }

    /**
     * Get the teams that the current user belongs to
     */
    public function fetchUserTeams(): AnonymousResourceCollection
    {
        $teams = $this->teams->fetchUserTeams();
        return TeamResource::collection($teams);
    }

    /**
     * @param string $slug
     * @return TeamResource
     */
    public function findBySlug(string $slug): TeamResource
    {
        $team = $this->teams->findWhereFirst('slug', $slug);
        return new TeamResource($team);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id): JsonResponse
    {
        $team = $this->teams->find($id);

        $this->authorize('delete', $team);

        $team->delete();

        return response()->json([
            'message' => 'Deleted!'
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $teamId
     * @param $userId
     * @return JsonResponse
     */
    public function removeFromTeam($teamId, $userId): JsonResponse
    {
        // get the team
        $team = $this->teams->find($teamId);
        $user = $this->users->find($userId);

        // check that the user is not the owner
        if ($user->isOwnerOfTeam($team)) {
            return response()->json([
                'message' => 'You are the team owner'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // check that the person sending the request
        // is either the owner of the team or the person
        // who wants to leave the team
        if (auth()->user() && ! auth()->user()->isOwnerOfTeam($team) && auth()->id() !== $user->id) {
            return response()->json([
                'message' => 'You cannot do this'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->invitations->removeUserFromTeam($team, $userId);

        return response()->json(['message' => 'Success'], Response::HTTP_OK);
    }
}
