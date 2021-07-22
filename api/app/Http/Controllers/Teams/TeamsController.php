<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\ITeam;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TeamsController extends Controller
{
    protected ITeam $teams;

    public function __construct(ITeam $teams)
    {
        $this->teams = $teams;
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
     * Get team by slug for Public View
     * @param string $slug
     */
    public function findBySlug(string $slug)
    {

    }

    /**
     * Destroy (delete) a team
     * @param int $id
     */
    public function destroy(int $id)
    {

    }
}
