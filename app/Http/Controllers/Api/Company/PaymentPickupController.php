<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PaymentPickupRepository;

use App\Http\Requests\PaymentPickups\{
    PopulateCompanyPaymentPickupsRequest as CompanyPopulateRequest,
    StorePaymentPickupRequest as StoreRequest,
    SelectPaymentPickupablesRequest as SelectPickupablesRequest,
    AddPaymentPickupPickupableRequest as AddPickupableRequest,
    RemovePaymentPickupPickupableRequest as RemovePickupableRequest,
    DeletePaymentPickupRequest as DeleteRequest
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
     * Add pickupable in payment pickup
     * 
     * @param AddPickupableRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function addPickupable(AddPickupableRequest $request)
    {
        $paymentPickup = $request->getPaymentPickup();
        $this->paymentPickup->setModel($paymentPickup);

        $pickupable = $request->getPickupable();
        $this->paymentPickup->addPickupable($pickupable);

        return apiResponse($this->paymentPickup);
    }

    /**
     * Select pickupables payment to be picked up
     * 
     * @param SelectPickupablesRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function addMultiplePickupables(SelectPickupablesRequest $request)
    {
        $paymentPickup = $request->getPaymentPickup();
        $this->paymentPickup->setModel($paymentPickup);

        $pickupables = $request->getPickupables();
        $this->paymentPickup->addMultiplePickupables($pickupables);

        return apiResponse($this->paymentPickup);
    }

    /**
     * Remove pickupable from payment pickup
     * 
     * @param RemovePickupableRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function removePickupable(RemovePickupableRequest $request)
    {
        $paymentPickup = $request->getPaymentPickup();
        $this->paymentPickup->setModel($paymentPickup);

        $pickupable = $request->getPickupable();
        $this->paymentPickup->removePickupable($pickupable);

        return api($this->paymentPickup);
    }

    /**
     * Remove multiple payment pickupable
     * 
     * @param SelectPickupablesRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function removeMultiplePickupables(SelectPickupablesRequest $request)
    {
        $paymentPickup = $request->getPaymentPickup();
        $this->paymentPickup->setModel($paymentPickup);

        $pickupables = $request->getPickupables();
        $this->paymentPickup->removeMultiplePickupables($pickupables);
    }

    /**
     * Delete payment pickup
     * 
     * @param DeleteRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $paymentPickup = $request->getPaymentPickup();
        $this->paymentPickup->setModel($paymentPickup);

        $force = $request->input('force');
        $this->paymentPickup->delete($force);

        return apiResponse($this->paymentPickup);
    }
}