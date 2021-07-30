<?php


namespace App\Repositories\Contracts;


use App\Models\Team;

interface IInvitation
{
    public function addUserToTeam(Team $team, int $user_id): void;
    public function removeUserFromTeam(Team $team, int $user_id): void;
}
