<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Customers\{
    SaveCustomerRequest as SaveRequest,
    FindCustomerRequest as FindRequest,
    DeleteCustomerRequest as DeleteRequest,
    RestoreCustomerRequest as RestoreRequest,
    PopulateCompanyCustomersRequest as PopulateRequest
};

use App\Http\Resources\CustomerResource;

use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    /**
     * Customer Repository Class container
     * 
     * @var \App\Repositories\CustomerRepository
     */
    private $customer;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\CustomerRepository  $customer
     * @return void
     */
    public function __construct(CustomerRepository $customer)
    {
    	$this->customer = $customer;
    }

    /**
     * Populate company customers
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyCustomers(PopulateRequest $request)
    {
        $options = $request->options();

    	$customers = $this->customer->all($options);
        $customers = $this->customer->paginate();
        $customers = CustomerResource::apiCollection($customers);

    	return response()->json(['customers' => $customers]);
    }

    /**
     * Populate soft-deleted customers
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedCustomers(PopulateRequest $request)
    {
        $options = $request->options();

        $customers = $this->customer->trasheds($options);
        $customers = $this->customer->paginate();
        $customers = CustomerResource::apiCollection($customers);

        return response()->json(['customers' => $customers]);
    }

    /**
     * Store customer
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
    	$customer = $this->customer->save($input);

    	return apiResponse($this->customer);
    }

    /**
     * View customer
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $customer = $request->getCustomer();
        $customer->load($request->relations());
        $customer = new CustomerResource($customer);

        return response()->json(['customer' => $customer]);
    }

    /**
     * Update customer
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $customer = $request->getCustomer();
    	$this->customer->setModel($customer);

        $input = $request->validated();
    	$customer = $this->customer->save($input);

    	return apiResponse($this->customer);
    }

    /**
     * Delete customer
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $customer = $request->getCustomer();
    	$this->customer->setModel($customer);

        $force = strtobool($request->input('force'));
    	$this->customer->delete($force);

    	return apiResponse($this->customer);
    }

    /**
     * Restore customer
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $customer = $request->getTrashedCustomer();
        $customer = $this->customer->setModel($customer);
        $customer = $this->customer->restore();

        return apiResponse($this->customer);
    }
}