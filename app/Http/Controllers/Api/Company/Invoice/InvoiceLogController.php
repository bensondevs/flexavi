<?php

namespace App\Http\Controllers\Api\Company\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\InvoiceLogs\PopulateInvoiceLogsRequest;
use App\Repositories\Invoice\InvoiceLogRepository;
use Illuminate\Http\JsonResponse;

class InvoiceLogController extends Controller
{
    /**
     * Invoice Log Repository Class Container
     *
     * @var InvoiceLogRepository
     */
    private InvoiceLogRepository $invoiceLogRepository;

    /**
     * Controller constructor method
     *
     * @param InvoiceLogRepository $invoiceLogRepository
     */
    public function __construct(InvoiceLogRepository $invoiceLogRepository)
    {
        $this->invoiceLogRepository = $invoiceLogRepository;
    }

    /**
     * Populate invoice logs
     *
     * @param PopulateInvoiceLogsRequest $request
     * @return JsonResponse
     */
    public function invoiceLogs(PopulateInvoiceLogsRequest $request): JsonResponse
    {
        $options = $request->options();
        $logs = $this->invoiceLogRepository->all($options, true);
        $logs = $this->invoiceLogRepository->groupByDate();
        return response()->json(compact('logs'));
    }
}
