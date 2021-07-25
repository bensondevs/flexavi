<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RegisterInvitations\SendInvitationRequest;

use App\Repositories\RegisterInvitationRepository;

class RegisterInvitationController extends Controller
{
    private $invitation;

    public function __construct(RegisterInvitationRepository $invitation)
    {
    	$this->invitation = $invitation;
    }

    public function sendInvitation(SendInvitationRequest $request)
    {
        $input = $request->validated();
    	$invitation = $this->invitation->send($input);

    	return apiResponse($this->invitation);
    }

    public function invitations(PopulateRequest $request)
    {
        $options = $request->options();

    	$invitations = $this->invitation->all();
        $invitations = $this->invitation->paginate();

    	return response()->json(['invitations' => $invitations]);
    }

    public function delete(FindInvitationRequest $request)
    {
        $invitation = $request->getInvitation();

    	$this->invitation->setModel($invitation);
    	$this->invitation->delete();

    	return apiResponse($this->invitation);
    }
}