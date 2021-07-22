<?php


namespace App\Repositories\Eloquent;


use App\MOdels\Team;
use App\Repositories\Contracts\ITeam;

class TeamRepository extends BaseRepository implements ITeam
{
    public function model(): string
    {
        return Team::class;
    }

    public function fetchUserTeams()
    {
        // TODO: Implement fetchUserTeams() method.
    }
}
