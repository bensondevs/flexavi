<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RegisterInvitations\SendInvitationRequest;

use App\Repositories\RegisterInvitationRepository;

class RegisterInvitationController extends Controller
{
    private $invitation;

    public function __construct(
    	RegisterInvitationRepository $invitation
    )
    {
    	$this->invitation = $invitation;
    }

    public function sendInvitation(SendInvitationRequest $request)
    {
    	$invitation = $this->invitation->send(
    		$request->onlyInRules()
    	);

    	return apiResponse($this->invitation, $invitation);
    }

    public function invitations(Request $request)
    {
    	$invitations = $this->invitation->all();

    	return response()->json([
    		'invitations' => $invitations
    	]);
    }

    public function delete(FindInvitationRequest $request)
    {
    	$this->invitation->setModel($request->getInvitation());
    	$this->invitation->delete();

    	return apiResponse($this->invitation);
    }
}