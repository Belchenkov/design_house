<?php


namespace App\Repositories\Eloquent;


use App\Models\Invitation;
use App\Models\Team;
use App\Repositories\Contracts\IInvitation;

class InvitationRepository extends BaseRepository implements IInvitation
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Invitation::class;
    }

    /**
     * @param Team $team
     * @param int $user_id
     */
    public function addUserToTeam(Team $team, int $user_id): void
    {
        $team->members()->attach($user_id);
    }

    /**
     * @param Team $team
     * @param int $user_id
     */
    public function removeUserFromTeam(Team $team, int $user_id): void
    {
        $team->members()->detach($user_id);
    }
}
