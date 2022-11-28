<?php

namespace App\Http\Controllers\Api\Company\PaymentPickup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\PaymentPickups\{PopulateCompanyPaymentPickupsRequest as CompanyPopulateRequest};
use App\Http\Requests\Company\PaymentPickups\DeletePaymentPickupRequest as DeleteRequest;
use App\Http\Requests\Company\PaymentPickups\FindPaymentPickupRequest as FindRequest;
use App\Http\Requests\Company\PaymentPickups\RestorePaymentPickupRequest as RestoreRequest;
use App\Http\Requests\Company\PaymentPickups\StorePaymentPickupRequest as StoreRequest;
use App\Http\Resources\PaymentPickup\PaymentPickupResource;
use App\Repositories\PaymentPickup\PaymentPickupItemRepository;
use App\Repositories\PaymentPickup\PaymentPickupRepository;
use Illuminate\Support\Facades\Response;

class PaymentPickupController extends Controller
{
    /**
     * Payment pickup repository class container
     *
     * @var PaymentPickupRepository
     */
    private $paymentPickup;

    /**
     * Payment pickup item repository class container
     *
     * @var PaymentPickupItemRepository
     */
    private $paymentPickupItem;

    /**
     * Controller constructor method
     *
     * @param PaymentPickupRepository $paymentPickup
     * @param PaymentPickupItemRepository $paymentPickupItem
     * @return void
     */
    public function __construct(
        PaymentPickupRepository     $paymentPickup,
        PaymentPickupItemRepository $paymentPickupItem
    )
    {
        $this->paymentPickup = $paymentPickup;
        $this->paymentPickupItem = $paymentPickupItem;
    }

    /**
     * Populate company payment pickups
     *
     * @param CompanyPopulateRequest $request
     * @return Response
     */
    public function companyPaymentPickups(CompanyPopulateRequest $request)
    {
        $options = $request->options();
        $paymentPickups = $this->paymentPickup->all($options, true);
        $paymentPickups = PaymentPickupResource::apiCollection($paymentPickups);

        return response()->json(['payment_pickups' => $paymentPickups]);
    }


    /**
     * Populate company trasheds payment pickups
     *
     * @param CompanyPopulateRequest $request
     * @return Response
     */
    public function paymentPickupTrasheds(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $paymentPickups = $this->paymentPickup->trasheds($options, true);
        $paymentPickups = PaymentPickupResource::apiCollection($paymentPickups);

        return response()->json(['payment_pickups' => $paymentPickups]);
    }

    /**
     * Create payment pickup
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $paymentPickup = $this->paymentPickup->save($request->paymentPickupData());
        $paymentPickup->insertPaymentPickupItems($request->paymentPickupItemsData());
        $paymentPickup = new PaymentPickupResource($paymentPickup->fresh());

        return apiResponse($this->paymentPickup, [
            'payment_pickup' => $paymentPickup
        ]);
    }

    /**
     * View payment pickup
     *
     * @param FindRequest $request
     * @return \Illuminate\Supplort\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $paymentPickup = new PaymentPickupResource($request->getPaymentPickup());
        return response()->json([
            'payment_pickup' => $paymentPickup,
        ]);
    }

    /**
     * Delete payment pickup
     *
     * @param DeleteRequest $request
     * @return Response
     */
    public function delete(DeleteRequest $request)
    {
        $paymentPickup = $request->getPaymentPickup();
        $this->paymentPickup->setModel($paymentPickup);

        $force = $request->input('force');
        $this->paymentPickup->delete($force);

        return apiResponse($this->paymentPickup);
    }

    /**
     * Restore payment pickup
     *
     * @param RestoreRequest $request
     * @return Response
     */
    public function restore(RestoreRequest $request)
    {
        $paymentPickup = $request->getPaymentPickup();

        $this->paymentPickup->setModel($paymentPickup);
        $this->paymentPickup->restore();

        return apiResponse($this->paymentPickup);
    }
}
