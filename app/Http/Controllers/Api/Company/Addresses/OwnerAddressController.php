<?php

namespace App\Http\Controllers\Api\Company\Addresses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Addresses\{
    PopulateAddressesRequest as PopulateRequest,
    SaveAddressRequest as SaveRequest,
    FindAddressRequest as FindRequest,
    DeleteAddressRequest as DeleteRequest,
    RestoreAddressRequest as RestoreRequest
};

use App\Http\Resources\AddressResource;

use App\Repositories\AddressRepository;

class OwnerAddressController extends Controller
{
    private $address;

    public function __construct(AddressRepository $address)
    {
        $this->address = $address;
    }

    public function ownerAddresses(PopulateRequest $request)
    {
        $options = $request->ownerOptions();

        $addresses = $this->address->all($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    public function ownerTrashedAddresses(PopulateRequest $request)
    {
        $options = $request->ownerOptions();

        $addresses = $this->address->trasheds($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    public function store(SaveRequest $request)
    {
        $owner = $request->getOwner();
        $this->address->setAddressable($owner);

        $addressData = $request->validated();
        $this->address->save($addressData);

        return apiResponse($this->address);
    }

    public function view(FindRequest $request)
    {
        $address = $request->getAddress();

        $relations = $request->relations();
        $address->load($relations);

        $address = new AddressResource($address);
        return response()->json(['address' => $address]);
    }

    public function update(SaveRequest $request)
    {
        $address = $request->getAddress();
        $this->address->setModel($address);

        $addressData = $request->validated();
        $this->address->save($addressData);

        return apiResponse($this->address);
    }

    public function delete(DeleteRequest $request)
    {
        $address = $request->getAddress();
        $this->address->setModel($address);

        $force = $request->input('force');
        $this->address->delete($force);

        return apiResponse($this->address);
    }

    public function restore(RestoreRequest $request)
    {
        $address = $request->getTrashedAddress();
        $address = $this->address->setModel($address);

        $address = $this->address->restore();
        $address = new AddressResource($address);
        
        return apiResponse($this->address, ['address' => $address]);
    }
}