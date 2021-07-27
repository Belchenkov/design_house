<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IInvitation;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    protected IInvitation $invitations;

    public function __construct(IInvitation $invitations)
    {
        $this->invitations = $invitations;
    }


}
