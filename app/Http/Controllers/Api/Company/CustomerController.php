<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Customers\SaveCustomerRequest as SaveRequest;
use App\Http\Requests\Customers\FindCustomerRequest as FindRequest;
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
    	$customers = $this->customer->all($request->options());
        $customers = $this->customer->paginate();
        $customers = CustomerResource::apiCollection($customers);

    	return response()->json(['customers' => $customers]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$customer = $this->customer->save($input);

    	return apiResponse($this->customer, ['customer' => $customer]);
    }

    public function update(SaveRequest $request)
    {
        $customer = $request->getCustomer();
    	$this->customer->setModel($customer);

        $input = $request->ruleWithCompany();
    	$customer = $this->customer->save($input);

    	return apiResponse($this->customer, ['customer' => $customer]);
    }

    public function delete(FindRequest $request)
    {
        $customer = $request->getCustomer();

    	$this->customer->setModel($customer);
    	$this->customer->delete();

    	return apiResponse($this->customer);
    }
}