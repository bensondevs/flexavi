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

class CustomerAddressController extends Controller
{
    /**
     * Address Repository Class Container
     * 
     * @var \App\Repositories\AddressRepository
     */
    private $address;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\AddressRepository  $address
     * @return void
     */
    public function __construct(AddressRepository $address)
    {
        $this->address = $address;
    }

    /**
     * Populate customer addresses
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function customerAddresses(PopulateRequest $request)
    {
        $options = $request->customerOptions();

        $addresses = $this->address->all($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    /**
     * Populate trashed customer address
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function customerTrashedAddresses(PopulateRequest $request)
    {
        $options = $request->customerOptions();

        $addresses = $this->address->trasheds($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    /**
     * Store customer address
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $customer = $request->getCustomer();
        $this->address->setAddressable($customer);

        $addressData = $request->validated();
        $this->address->save($addressData);

        return apiResponse($this->address);
    }

    /**
     * View customer address and load relationships
     * 
     * @param FindRequest  $request
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
     * @param SaveRequest  $request
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
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $address = $request->getAddress();
        $this->address->setModel($address);

        $force = $request->input('force');
        $this->address->delete($force);

        return apiResponse($this->address);
    }

    /**
     * Restore customer address
     * 
     * @param RestoreRequest  $request
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
