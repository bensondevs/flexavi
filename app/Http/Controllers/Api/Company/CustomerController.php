<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Customers\SaveCustomerRequest as SaveRequest;
use App\Http\Requests\Customers\FindCustomerRequest as FindRequest;
use App\Http\Requests\Customers\DeleteCustomerRequest as DeleteRequest;
use App\Http\Requests\Customers\RestoreCustomerRequest as RestoreRequest;
use App\Http\Requests\Customers\PopulateCompanyCustomersRequest as PopulateRequest;

use App\Http\Resources\CustomerResource;

use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    private $customer;

    public function __construct(CustomerRepository $customer)
    {
    	$this->customer = $customer;
    }

    public function companyCustomers(PopulateRequest $request)
    {
        $options = $request->options();

    	$customers = $this->customer->all($options);
        $customers = $this->customer->paginate();
        $customers = CustomerResource::apiCollection($customers);

    	return response()->json(['customers' => $customers]);
    }

    public function trashedCustomers(PopulateRequest $request)
    {
        $options = $request->options();

        $customers = $this->customer->trasheds($options);
        $customers = $this->customer->paginate();
        $customers = CustomerResource::apiCollection($customers);

        return response()->json(['customers' => $customers]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$customer = $this->customer->save($input);

    	return apiResponse($this->customer);
    }

    public function update(SaveRequest $request)
    {
        $customer = $request->getCustomer();
    	$this->customer->setModel($customer);

        $input = $request->ruleWithCompany();
    	$customer = $this->customer->save($input);

    	return apiResponse($this->customer);
    }

    public function delete(DeleteRequest $request)
    {
        $customer = $request->getCustomer();
    	$this->customer->setModel($customer);

        $force = strtobool($request->input('force'));
    	$this->customer->delete($force);

    	return apiResponse($this->customer);
    }

    public function restore(RestoreRequest $request)
    {
        $customer = $request->getTrashedCustomer();
        $customer = $this->customer->setModel($customer);
        $customer = $this->customer->restore();

        return apiResponse($this->customer);
    }
}