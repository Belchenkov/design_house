<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ITeam;
use Illuminate\Http\Request;

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
     */
    public function store(Request $request)
    {

    }

    /**
     * Update team information
     * @param Request $request
     */
    public function update(Request $request)
    {

    }

    /**
     * Find a team by its ID
     * @param int $id
     */
    public function findById(int $id)
    {

    }

    /**
     * Get the teams that the current user belongs to
     */
    public function fetchUserTeams()
    {

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
