<?php

namespace App\Http\Controllers\Api\Company\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Invoices\{ChangeInvoiceStatusRequest,
    DeleteInvoiceRequest as DeleteRequest,
    DraftInvoiceRequest as DraftRequest,
    FindInvoiceRequest as FindRequest,
    PopulateCompanyInvoicesRequest as PopulateRequest,
    PrintInvoiceRequest as PrintRequest,
    RestoreInvoiceRequest as RestoreRequest,
    SendInvoiceRequest as SendRequest,
};
use App\Http\Resources\Invoice\InvoiceResource;
use App\Repositories\Invoice\InvoiceRepository;
use App\Services\Invoice\InvoiceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @see \Tests\Feature\Dashboard\Company\Invoice\InvoiceTest
 *      To the controller unit tester class.
 */
class InvoiceController extends Controller
{
    /**
     * Invoice Repository Class Container
     *
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;

    /**
     * Invoice Service Class Container
     *
     * @var InvoiceService
     */
    private InvoiceService $invoiceService;

    /**
     * Controller constructor method
     *
     * @param InvoiceRepository $invoiceRepository
     * @param InvoiceService $invoiceService
     */
    public function __construct(
        InvoiceRepository $invoiceRepository,
        InvoiceService    $invoiceService
    )
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Populate company invoices
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function companyInvoices(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $invoices = $this->invoiceRepository->all($options, true);
        $invoices = InvoiceResource::apiCollection($invoices);

        return response()->json(['invoices' => $invoices]);
    }


    /**
     * Populate company invoices
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function companyTrashedInvoices(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $invoices = $this->invoiceRepository->trasheds($options, true);
        $invoices = InvoiceResource::apiCollection($invoices);

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Draft invoice
     *
     * @param DraftRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function draft(DraftRequest $request): JsonResponse
    {
        $invoice = $request->has('invoice_id') ? $request->getInvoice() : null;
        $service = $this->invoiceService->save($invoice, $request->invoiceData());
        return apiResponse($service);
    }

    /**
     * View invoice
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function view(FindRequest $request): JsonResponse
    {
        $invoice = $request->getInvoice();
        $invoice->load($request->relations());
        $invoice = new InvoiceResource($invoice);

        return response()->json(['invoice' => $invoice]);
    }

    /**
     * Restore invoice
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     */
    public function restore(RestoreRequest $request): JsonResponse
    {
        $invoice = $request->getInvoice();
        $this->invoiceRepository->setModel($invoice);
        $invoice = $this->invoiceRepository->restore();
        return apiResponse($this->invoiceRepository, ['invoice' => $invoice]);
    }

    /**
     * Send Invoice to target email or customer email
     *
     * @param SendRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function send(SendRequest $request): JsonResponse
    {
        if (!$request->has('invoice_id')) {
            return apiResponse($this->invoiceService->save(null, $request->invoiceData()));
        }

        $invoice = $request->getInvoice();

        if ($invoice->isDrafted()) {
            return apiResponse($this->invoiceService->save($invoice, $request->invoiceData()));
        }

        return apiResponse($this->invoiceService->resend($invoice));
    }

    /**
     * Print invoice
     *
     * @param PrintRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function print(PrintRequest $request): JsonResponse
    {
        $this->invoiceRepository->setModel($request->getInvoice());
        $this->invoiceRepository->print();
        return apiResponse($this->invoiceRepository);
    }


    /**
     * Delete invoice
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        $invoice = $request->getInvoice();
        $this->invoiceRepository->setModel($invoice);

        $force = $request->input('force');
        $this->invoiceRepository->delete($force);

        return apiResponse($this->invoiceRepository);
    }

    /**
     * Change invoice status
     *
     * @param ChangeInvoiceStatusRequest $request
     * @return JsonResponse
     */
    public function changeStatus(ChangeInvoiceStatusRequest $request): JsonResponse
    {
        $invoice = $request->getInvoice();
        $this->invoiceRepository->setModel($invoice);
        $invoice = $this->invoiceRepository->changeStatus($request->input('status'));
        return apiResponse($this->invoiceRepository, ['invoice' => $invoice]);
    }
}
