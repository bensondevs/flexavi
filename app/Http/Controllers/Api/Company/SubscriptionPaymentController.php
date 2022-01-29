<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\SubscriptionPaymentRepository as PaymentRepository;

class SubscriptionPaymentController extends Controller
{
    /**
     * Subscription payment repository class container
     * 
     * @var \App\Repositories\SubscriptionPaymentRepository|null
     */
    private $payment;

    /**
     * Controller constructor method
     * 
     * @param  PaymentRepository  $payment
     */
    public function __construct(PaymentRepository $payment)
    {
        $this->payment = $payment;
    }

    /**
     * View subscription payment
     * 
     * @param  FindRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function view()
    {
        //
    }

    /**
     * Pay using mollie payment gateway
     * 
     * @param  MolliePayRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function payUsingMollie()
    {
        //
    }
}
