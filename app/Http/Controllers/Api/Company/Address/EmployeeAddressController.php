<?php

namespace App\Http\Controllers\Api\Company\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Addresses\{FindAddressRequest as FindRequest};
use App\Http\Requests\Company\Addresses\DeleteAddressRequest as DeleteRequest;
use App\Http\Requests\Company\Addresses\PopulateAddressesRequest as PopulateRequest;
use App\Http\Requests\Company\Addresses\RestoreAddressRequest as RestoreRequest;
use App\Http\Requests\Company\Addresses\SaveAddressRequest as SaveRequest;
use App\Http\Resources\Address\AddressResource;
use App\Repositories\Address\AddressRepository;

class EmployeeAddressController extends Controller
{
    /**
     * Address Repository Class Container
     *
     * @var AddressRepository
     */
    private $address;

    /**
     * Controller constructor method
     *
     * @param AddressRepository $address
     * @return void
     */
    public function __construct(AddressRepository $address)
    {
        $this->address = $address;
    }

    /**
     * Populate Employee Addresses
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function employeeAddresses(PopulateRequest $request)
    {
        $options = $request->employeeOptions();

        $addresses = $this->address->all($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    /**
     * Populate employee trashed addresses
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function employeeTrashedAddresses(PopulateRequest $request)
    {
        $options = $request->employeeOptions();

        $addresses = $this->address->trasheds($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    /**
     * Store new address and attach to employee
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $customer = $request->getEmployee();
        $this->address->setAddressable($customer);

        $addressData = $request->validated();
        $this->address->save($addressData);

        return apiResponse($this->address);
    }

    /**
     * View customer address
     *
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $address = $request->getAddress();

        $relations = $request->relations();
        $address->load($relations);

        return response()->json(['address' => $address]);
    }

    /**
     * Update customer address
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $address = $request->getAddress();
        $this->address->setModel($address);

        $addressData = $request->validated();
        $this->address->save($addressData);

        return apiResponse($this->address);
    }

    /**
     * Delete customer address
     *
     * @param DeleteRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $address = $request->getAddress();
        $this->address->setModel($address);

        $force = $request->input('force');
        $this->address->delete();

        return apiResponse($this->address);
    }

    /**
     * Restore customer address
     *
     * @param RestoreRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $address = $request->getTrashedAddress();

        $address = $this->address->setModel($address);
        $address = $this->address->restore();

        return apiResponse($this->address, ['address' => $address]);
    }
}
