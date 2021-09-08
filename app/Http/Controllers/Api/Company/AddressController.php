<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Addresses\{
    PopulateUserAddressesRequest as UserPopulateRequest,
    PopulateEmployeeAddressesRequest as EmployeePopulateRequest
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

    public function userAddresses(UserPopulateRequest $request)
    {
        $options = $request->options();

        $addresses = $this->address->all($options);
        $addresses = $this->address->paginate();
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    public function employeeAddresses(EmployeePopulateRequest $request)
    {
        $options = $request->options();
        
        $addresses = $this->address->all($options);
        $addresses = $this->address->paginate();
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    public function customerAddresses(CustomerPopulateRequest $request)
    {
        $options = $request->options();
        
        $addresses = $this->address->all($options);
        $addresses = $this->address->paginate();
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    public function deletedAddresses(DeletedPopulateRequest $request)
    {
        $options = $request->options();
        
        $addresses = $this->address->all($options);
        $addresses = $this->address->paginate();
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $address = $this->address->save($input);

        return apiResponse($this->address);
    }

    public function update(SaveRequest $request)
    {
        $address = $request->getAddress();
        $address = $this->address->setModel($addres);

        $input = $request->validated();
        $address = $this->address->save($input);

        return apiResponse($this->address);
    }

    public function delete(DeleteRequest $request)
    {
        $address = $request->getAddress();

        $this->address->setModel($addres);
        $this->address->delete();

        return apiResponse($this->address);
    }
}
