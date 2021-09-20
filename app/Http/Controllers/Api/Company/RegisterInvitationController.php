<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RegisterInvitations\InviteOwnerRequest;
use App\Http\Requests\RegisterInvitations\InviteEmployeeRequest;

use App\Repositories\RegisterInvitationRepository;

class RegisterInvitationController extends Controller
{
    private $invitation;

    public function __construct(RegisterInvitationRepository $invitation)
    {
        $this->invitation = $invitation;
    }

    public function inviteEmployee(InviteEmployeeRequest $request)
    {
        $input = $request->invitationData();
        $invitation = $this->invitation->sendInvitation($input);

        return apiResponse($this->invitation, ['invitation' => $invitation]);
    }

    public function inviteOwner(InviteOwnerRequest $request)
    {
        $input = $request->invitationData();
        $invitation = $this->invitation->sendInvitation($input);

        return apiResponse($this->invitation, ['invitation' => $invitation]);
    }
}
