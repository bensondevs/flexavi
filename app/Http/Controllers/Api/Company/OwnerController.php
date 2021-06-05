<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Owners\SaveOwnerRequest as SaveRequest;
use App\Http\Requests\Owners\DeleteOwnerRequest as DeleteRequest;
use App\Http\Requests\Owners\PopulateCompanyOwnersRequest as PopulateRequest;

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
        $owners = $this->owner->paginate();
        $owners->data = OwnerResource::collection($owners);

        return response()->json(['owners' => $owners]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
        $owner = $this->owner->save($input);

        return apiResponse($this->owner, ['owner' => $owner]);
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
        $this->owner->delete();

        return apiResponse($this->owner);
    }
}
