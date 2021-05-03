<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\OwnerRepository;

class OwnerController extends Controller
{
    private $owner;

    public function __construct(OwnerRepository $owner)
    {
    	$this->owner = $owner;
    }

    public function owners()
    {
    	$owners = $this->owner->ofUser(auth()->user());

    	return response()->json(['owners' => $owners]);
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
