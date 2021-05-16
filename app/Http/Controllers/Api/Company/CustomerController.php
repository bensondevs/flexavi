<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

use App\Http\Requests\Customers\SaveCustomerRequest;
use App\Http\Requests\Customers\FindCustomerRequest;
use App\Http\Requests\Customers\PopulateCompanyCustomersRequest;

use App\Http\Resources\CustomerResource;

use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    private $customer;

    public function __construct(CustomerRepository $customer)
    {
    	$this->customer = $customer;
    }

    public function companyCustomers(PopulateCompanyCustomersRequest $request)
    {
    	$customers = $this->customer->all($request->options());
        $customers = $this->customer->paginate();
        $customers->data = CustomerResource::collection($customers);

    	return response()->json(['customers' => $customers]);
    }

    public function store(SaveCustomerRequest $request)
    {
    	$customer = $this->customer->save(
    		$request->onlyInRules()
    	);

    	return apiResponse($this->customer, $customer);
    }

    public function update(SaveCustomerRequest $request)
    {
    	$this->customer->setModel($request->getCustomer());
    	$customer = $this->customer->save(
    		$request->onlyInRules()
    	);

    	return apiResponse($this->customer, $customer);
    }

    public function delete(FindCustomerRequest $request)
    {
    	$this->customer->setModel($request->getCustomer());
    	$this->customer->delete();

    	return apiResponse($this->customer);
    }
}
