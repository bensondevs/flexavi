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
    private $owner;

    public function __construct(CompanyOwnerRepository $owner)
    {
        $this->owner = $owner;
    }

    public function companyOwners(PopulateRequest $request)
    {
        $options = $request->options();

        $owners = $this->owner->all($options);
        $owners = $this->owner->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    public function inviteableOwners(PopulateRequest $request)
    {
        $options = $request->options();

        $owners = $this->owner->inviteables($options);
        $owners = $this->owner->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    public function trashedOwners(PopoulateRequest $request)
    {
        $options = $request->options();

        $owners = $this->owner->trasheds($options);
        $owners = $this->owner->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
        $owner = $this->owner->save($input);
        $owner = new OwnerResource($owner->fresh());

        return apiResponse($this->owner, ['owner' => $owner]);
    }

    public function view(FindRequest $request)
    {
        $owner = $request->getOwner();
        $owner = new OwnerResource($owner);

        return response()->json(['owner' => $owner]);
    }

    public function update(SaveRequest $request)
    {
        $owner = $request->getOwner();
        $owner = $this->owner->setModel($owner);

        $input = $request->ruleWithCompany();
        $owner = $this->owner->save($input);

        return apiResponse($this->owner, ['owner' => $owner]);
    }

    public function delete(DeleteRequest $request)
    {
        $owner = $request->getTargetedOwner();
        $this->owner->setModel($owner);

        $force = strtobool($request->input('force'));
        $this->owner->delete($force);

        return apiResponse($this->owner);
    }

    public function restore(RestoreRequest $request)
    {
        $owner = $request->getTrashedOwner();
        $owner = $this->owner->setModel($owner);
        $owner = $this->owner->restore();
        $owner = new OwnerResource($owner);

        return apiResponse($this->owner, ['owner' => $owner]);
    }
}
