<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Owners\CurrentOwnerRequest;
use App\Http\Requests\RegisterInvitations\SendInvitationRequest;

use App\Repositories\OwnerRepository;

class OwnerController extends Controller
{
    private $owner;
    private $invitation;

    public function __construct(OwnerRepository $owner)
    {
    	$this->owner = $owner;
    }

    public function owner(CurrentOwnerRequest $request)
    {
        $owner = $request->getOwner();

    	return response()->json(['owner' => $owner]);
    }

    public function inviteUser(SendInvitationRequest $request)
    {

    }

    public function validateBankAccount(Request $request)
    {
    	$this->owner->find($request->input('id'));
    	$this->owner->validateBankAccount();

    	return apiResponse(
    		$this->owner, 
    		$this->owner->getModel()
    	);
    }
}
