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
     * Populate company addresses
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyAddresses(PopulateRequest $request)
    {
        $options = $request->companyOptions();

        $addresses = $this->address->all($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    /**
     * Populate company trashed addresses
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyTrashedAddresses(PopulateRequest $request)
    {
        $options = $request->companyOptions();

        $addresses = $this->address->trasheds($options, true);
        $addresses = AddressResource::apiCollection($addresses);

        return response()->json(['addresses' => $addresses]);
    }

    /**
     * Store company address
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $company = $request->getCompany();
        $this->address->setAddressable($company);

        $input = $request->validated();
        $address = $this->address->save($input);

        return apiResponse($this->address);
    }

    /**
     * View company address
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
     * Update company address
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $address = $request->getAddress();
        $address = $this->address->setModel($address);

        $input = $request->validated();
        $address = $this->address->save($input);

        return apiResponse($this->address);
    }

    /**
     * Delete company address
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
     * Restore company address
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $address = $request->getTrashedAddress();

        $this->address->setModel($address);
        $this->address->restore();

        return apiResponse($this->address);
    }
}

