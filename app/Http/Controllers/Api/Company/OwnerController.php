<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Owners\{
    SaveOwnerRequest as SaveRequest,
    FindOwnerRequest as FindRequest,
    DeleteOwnerRequest as DeleteRequest,
    PopulateCompanyOwnersRequest as PopulateRequest
};

use App\Http\Resources\OwnerResource;

use App\Repositories\CompanyOwnerRepository;

class OwnerController extends Controller
{
    /**
     * Owner Repository Class Container
     * 
     * @var \App\Repositories\CompanyOwnerRepository
     */
    private $owner;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\CompanyOwnerRepository  $owner
     * @return void
     */
    public function __construct(CompanyOwnerRepository $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Populate company owners
     * 
     * @param PopulateRequest  $request
     * @return void 
     */
    public function companyOwners(PopulateRequest $request)
    {
        $options = $request->options();

        $owners = $this->owner->all($options);
        $owners = $this->owner->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    /**
     * Populate company inviteable owners
     * 
     * @param PopulateRequest  $request
     * @return void
     */
    public function inviteableOwners(PopulateRequest $request)
    {
        $options = $request->options();

        $owners = $this->owner->inviteables($options);
        $owners = $this->owner->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    /**
     * Populate company trashed owners
     * 
     * @param PopulateRequest  $request
     * @return void
     */
    public function trashedOwners(PopoulateRequest $request)
    {
        $options = $request->options();

        $owners = $this->owner->trasheds($options);
        $owners = $this->owner->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    /**
     * Store company owners
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
        $owner = $this->owner->save($input);
        $owner = new OwnerResource($owner->fresh());

        return apiResponse($this->owner, ['owner' => $owner]);
    }

    /**
     * View company owners
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $owner = $request->getOwner();
        $owner = new OwnerResource($owner);

        return response()->json(['owner' => $owner]);
    }

    /**
     * Update company owners
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $owner = $request->getOwner();
        $owner = $this->owner->setModel($owner);

        $input = $request->ruleWithCompany();
        $owner = $this->owner->save($input);

        return apiResponse($this->owner, ['owner' => $owner]);
    }

    /**
     * Delete company owners
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $owner = $request->getOwner();
        $this->owner->setModel($owner);

        $force = $request->input('force', false);
        $this->owner->delete($force);

        return apiResponse($this->owner);
    }

    /**
     * Restore company owners
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $owner = $request->getTrashedOwner();
        $owner = $this->owner->setModel($owner);
        $owner = $this->owner->restore();
        $owner = new OwnerResource($owner);

        return apiResponse($this->owner, ['owner' => $owner]);
    }
}
