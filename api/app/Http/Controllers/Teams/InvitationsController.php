<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationToJoinTeam;
use App\Models\Team;
use App\Repositories\Contracts\IInvitation;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class InvitationsController extends Controller
{
    protected IInvitation $invitations;
    protected ITeam $teams;
    protected IUser $users;

    public function __construct(
        ITeam $teams,
        IInvitation $invitations,
        IUser $users
    )
    {
        $this->invitations = $invitations;
        $this->teams = $teams;
        $this->users = $users;
    }

    public function invite(Request $request, int $team_id): JsonResponse
    {
        $team = $this->teams->find($team_id);

        $this->validate($request, [
           'email' => ['required', 'email']
        ]);

        // Check if user owns the team
        $user = auth()->user();

        if ($user && ! $user->isOwnerOfTeam($team)) {
            return response()->json([
                'email' => 'You are not the team owner!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Check if email has a pending invitation
        if ($team && $team->hasPendingInvite($request->email)) {
            return response()->json([
                'email' => 'Email already has a pending invite!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get recipient by email
        $recipient = $this->users->findByEmail($request->email);

        // if the recipient does not exist, send invitation to join the team
        if (! $recipient) {
            $this->createInvitation(false, $team, $request->email);

            return response()->json([
                'message' => 'Invitation sent to user'
            ], Response::HTTP_OK);
        }

        // check if the team already has the user
        if ($team->hasUser($recipient)) {
            return response()->json([
                'message' => 'This user seems to be a team member already'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // Send the invitation to the user
        $this->createInvitation(true, $team, $request->email);

        return response()->json([
            'message' => 'Invitation sent to user'
        ], Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function resend(int $id): JsonResponse
    {
        if (! $invitation = $this->invitations->find($id)) {
            return response()->json([
                'message' => 'Invitation is not found!'
            ], Response::HTTP_NOT_FOUND);
        }

        if (auth()->user() && ! auth()->user()->isOwnerOfTeam($invitation->team)) {
            return response()->json([
                'email' => 'You are not the team owner!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $recipient = $this->users->findByEmail($invitation->recipient_email);

        Mail::to($invitation->recipient_email)
            ->send(new SendInvitationToJoinTeam($invitation, !is_null($recipient)));

        return response()->json([
            'message' => 'Invitation resent'
        ], Response::HTTP_OK);
    }

    /**
     * @param bool $user_exists
     * @param Team $team
     * @param string $email
     */
    private function createInvitation(bool $user_exists, Team $team, string $email): void
    {
        $invitation = $this->invitations->create([
            'team_id' => $team->id,
            'sender_id' => auth()->id(),
            'recipient_email' => $email,
            'token' => md5(uniqid(microtime(), true))
        ]);

        Mail::to($email)
            ->send(new SendInvitationToJoinTeam($invitation, $user_exists));
    }
}
