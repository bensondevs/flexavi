<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Repository Class Container
     *
     * @var \App\Repositories\Customer\CustomerRepository
     */
    private $customer;

    /**
     * Controller constructor method
     *
     * @param CustomerRepository  $customer
     * @return void
     */
    public function __construct(CustomerRepository $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get current customer information
     *
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function current(Request $request)
    {
        return response()->json(['customer' => $request->user()]);
    }
}
