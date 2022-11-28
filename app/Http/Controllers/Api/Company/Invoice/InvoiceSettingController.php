<?php

namespace App\Http\Controllers\Api\Company\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\InvoiceSettings\{FindInvoiceSettingRequest as FindRequest,
    UpdateInvoiceSettingRequest as UpdateRequest
};
use App\Http\Resources\Invoice\InvoiceSettingResource;
use App\Repositories\Invoice\InvoiceSettingRepository;
use Illuminate\Http\JsonResponse;

class InvoiceSettingController extends Controller
{

    /**
     * Invoice setting repository container.
     *
     * @var InvoiceSettingRepository
     */
    private InvoiceSettingRepository $invoiceSettingRepository;

    /**
     * Controller constructor method.
     *
     * @param InvoiceSettingRepository $invoiceSettingRepository
     */
    public function __construct(InvoiceSettingRepository $invoiceSettingRepository)
    {
        $this->invoiceSettingRepository = $invoiceSettingRepository;
    }

    /**
     * Find Invoice Reminder
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function invoiceSetting(FindRequest $request): JsonResponse
    {
        return response()->json([
            'invoice_setting' => new InvoiceSettingResource($request->getInvoiceSetting())
        ]);
    }

    /**
     * Update Invoice Reminder
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function updateSetting(UpdateRequest $request): JsonResponse
    {
        $this->invoiceSettingRepository->setModel($request->getInvoiceSetting());
        $setting = $this->invoiceSettingRepository->save($request->except(['invoice_id']));
        return apiResponse($this->invoiceSettingRepository, [
            'invoice_setting' => new InvoiceSettingResource($setting)
        ]);
    }
}
