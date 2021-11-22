<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RegisterInvitations\{
    InviteOwnerRequest, InviteEmployeeRequest
};

use App\Repositories\RegisterInvitationRepository;

class RegisterInvitationController extends Controller
{
    /**
     * Register Invitation Repository Class Container
     * 
     * @var \App\Repositories\RegisterInvitationRepository
     */
    private $invitation;

    public function __construct(RegisterInvitationRepository $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Invite employee to register as user
     * 
     * @param InviteEmploteeRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function inviteEmployee(InviteEmployeeRequest $request)
    {
        $input = $request->invitationData();
        $invitation = $this->invitation->sendInvitation($input);

        return apiResponse($this->invitation, ['invitation' => $invitation]);
    }

    /**
     * Invite owner to register as user
     * 
     * @param InviteOwnerRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function inviteOwner(InviteOwnerRequest $request)
    {
        $input = $request->invitationData();
        $invitation = $this->invitation->sendInvitation($input);

        return apiResponse($this->invitation, ['invitation' => $invitation]);
    }
}
