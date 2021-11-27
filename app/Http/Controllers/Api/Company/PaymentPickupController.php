<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PaymentPickupRepository;

use App\Http\Requests\PaymentPickups\{
    PopulateCompanyPaymentPickupsRequest as CompanyPopulateRequest,
    StorePaymentPickupRequest as StoreRequest
};

class PaymentPickupController extends Controller
{
    /**
     * Payment pickup repository class container
     * 
     * @var \App\Repositories\PaymentPickupRepository
     */
    private $paymentPickup;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\PaymentPickupRepository  $paymentPickup 
     * @return void
     */
    public function __construct(PaymentPickupRepository $paymentPickup)
    {
        $this->paymentPickup = $paymentPickup;
    }

    /**
     * Populate company payment pickups
     * 
     * @param CompanyPopulateRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function companyPaymentPickups(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $paymentPickups = $this->paymentPickup->all($options, true);
        $paymentPickups = PaymentPickupResource::apiCollection($paymentPickups);

        return response()->json(['payment_pickups' => $paymentPickups]);
    }

    /**
     * Populate appointment payment pickups
     * 
     * @param AppointmentPopulateRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function appointmentPaymentPickups(AppointmentPopulateRequest $request)
    {
        $options = $request->options();

        $paymentPickups = $this->paymentPickup->all($options, true);
        $paymentPickups = PaymentPickupResource::apiCollection($paymentPickups);

        return response()->json(['payment_pickups' => $paymentPickups]);
    }

    /**
     * Create payment pickup
     * 
     * @param StoreRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $this->paymentPickup->save($input);

        return apiResponse($this->paymentPickup);
    }

    /**
     * Select revenue to be picked up
     * 
     * @param SelectRevenuesRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function selectRevenues(SelectRevenuesRequest $request)
    {
        //
    }

    /**
     * Update payment pickup
     * 
     * @param UpdateRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function update()
    {
        //
    }

    /**
     * Delete payment pickup
     * 
     * @param DeleteRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function delete()
    {
        //
    }
}
