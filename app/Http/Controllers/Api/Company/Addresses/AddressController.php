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

class AddressController extends Controller
{
    private $address;

    public function __construct(AddressRepository $address)
    {
        $this->address = $address;
    }

    public function companyAddresses(PopulateRequest $request)
    {
        $options = $request->companyOptions();

        $addresses = $this->address->all($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    public function store(SaveRequest $request)
    {
        $company = $request->getCompany();
        $this->address->setAddressable($company);

        $input = $request->validated();
        $address = $this->address->save($input);

        return apiResponse($this->address);
    }

    public function view(FindRequest $request)
    {
        $address = $request->getAddress();

        $relations = $request->relations();
        $address->load($relations);

        return response()->json(['address' => $address]);
    }

    public function update(SaveRequest $request)
    {
        $address = $request->getAddress();
        $address = $this->address->setModel($address);

        $input = $request->validated();
        $address = $this->address->save($input);

        return apiResponse($this->address);
    }

    public function delete(DeleteRequest $request)
    {
        $address = $request->getAddress();

        $this->address->setModel($address);
        $this->address->delete();

        return apiResponse($this->address);
    }

    public function restore(RestoreRequest $request)
    {
        $address = $request->getTrashedAddress();

        $this->address->setModel($address);
        $this->address->restore();

        return apiResponse($this->address);
    }
}

