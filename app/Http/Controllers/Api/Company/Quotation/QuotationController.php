<?php

namespace App\Http\Controllers\Api\Company\Quotation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Quotations\{DeleteQuotationRequest as DeleteRequest,
    DeleteQuotationSignedDocumentRequest as DeleteSignedDocRequest,
    DraftQuotationRequest as DraftRequest,
    FindQuotationRequest as FindRequest,
    GenerateQuotationInvoiceRequest as GenerateInvoiceRequest,
    NullifyQuotationRequest as NullifyRequest,
    PopulateCompanyQuotationsRequest as CompanyPopulateRequest,
    PopulateCustomerQuotationsRequest as CustomerPopulateRequest,
    PopulateEmployeeQuotationsRequest as EmployeePopulateRequest,
    PopulateQuotationLogsRequest as LogsPopulateRequest,
    PrintQuotationRequest,
    RestoreQuotationRequest as RestoreRequest,
    SaveQuotationSignedDocumentRequest as SaveSignedDocRequest,
    SendQuotationRequest as SendRequest
};
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Resources\Quotation\QuotationResource;
use App\Models\Quotation\Quotation;
use App\Repositories\{Quotation\QuotationLogRepository, Quotation\QuotationRepository};
use App\Services\Quotation\QuotationService;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest
 *      To the controller unit tester class.
 */
class QuotationController extends Controller
{
    /**
     * Quotation Repository Class Container
     *
     * @var QuotationRepository
     */
    private QuotationRepository $quotationRepository;

    /**
     * Quotation Service Class Container
     *
     * @var QuotationService
     */
    private QuotationService $quotationService;

    /**
     * Quotation Log Repository Class Container
     *
     * @var QuotationLogRepository
     */
    private QuotationLogRepository $quotationLogRepository;

    /**
     * Controller constructor method
     *
     * @param QuotationRepository $quotationRepository
     * @param QuotationService $quotationService
     * @param QuotationLogRepository $quotationLogRepository
     */
    public function __construct(
        QuotationRepository    $quotationRepository,
        QuotationService       $quotationService,
        QuotationLogRepository $quotationLogRepository
    )
    {
        $this->quotationRepository = $quotationRepository;
        $this->quotationService = $quotationService;
        $this->quotationLogRepository = $quotationLogRepository;
    }

    /**
     * Populate company quotations
     *
     * @param CompanyPopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_populate_company_quotations()
     *      to controller's feature test
     */
    public function companyQuotations(CompanyPopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $quotations = $this->quotationRepository->all($options);
        $quotations = $this->quotationRepository->paginate();
        $quotations = QuotationResource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    /**
     * Populate customer quotations
     *
     * @param CustomerPopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_populate_customer_quotations()
     *      to controller's feature test
     */
    public function customerQuotations(CustomerPopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $quotations = $this->quotationRepository->all($options);
        $quotations = $this->quotationRepository->paginate();
        $quotations = QuotationResource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    /**
     * Populate employee quotations
     *
     * @param EmployeePopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_populate_employee_quotations()
     *      to controller's feature test
     */
    public function employeeQuotations(EmployeePopulateRequest $request): JsonResponse
    {
        $employee = $request->getEmployee();
        $quotations = $employee->quotations()->paginate()->toArray()['data'];
        $quotations = Quotation::hydrate($quotations);
        $quotations = $this->quotationRepository->setCollection(collect($quotations));
        $quotations = $this->quotationRepository->paginate();
        $quotations = QuotationResource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    /**
     * Populate trashed quotations
     *
     * @param CompanyPopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_populate_trashed_quotations()
     *      to controller's feature test
     */
    public function trashedQuotations(CompanyPopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $quotations = $this->quotationRepository->trasheds($options);
        $quotations = $this->quotationRepository->paginate();
        $quotations = QuotationResource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    /**
     * Draft company quotation
     *
     * @param DraftRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function draft(DraftRequest $request): JsonResponse
    {
        $quotation = $request->has('quotation_id') ? $request->getQuotation() : null;
        $service = $this->quotationService->save(
            $quotation,
            $request->quotationData(),
            false,
        );
        return apiResponse($service);
    }

    /**
     * View company quotation
     *
     * @param FindRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_view_quotation()
     *      to controller's feature test
     */
    public function view(FindRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();
        $quotation = new QuotationResource($quotation);

        return response()->json(['quotation' => $quotation]);
    }

    /**
     * Print quotation.
     *
     * This will set the status of the quotation to Sent
     *
     * @param PrintQuotationRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_print_quotation()
     *      To the method unit tester method.
     */
    public function print(PrintQuotationRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();
        $quotation->setSent();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully print the quotation and set status as Sent!',
            'quotation' => new QuotationResource($quotation->fresh()),
        ]);
    }

    /**
     * Send quotation
     *
     * @param SendRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_send_quotation()
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_save_and_send_uncreated_quotation()
     *      to controller's feature test
     */
    public function send(SendRequest $request): JsonResponse
    {
        // If the quotation is drafted or never been created
        // Save the change first to ensure the latest change implemented
        $quotation = $request->getQuotation();
        if (is_null($quotation) or $quotation->isDrafted()) {
            $quotation = $this->quotationService->save(
                $quotation,
                $request->quotationData(),
            )->getModel();
        }

        return apiResponse($this->quotationService->send($quotation));
    }

    /**
     * Generate invoice from quotation
     *
     * @param GenerateInvoiceRequest $request
     * @return JsonResponse
     * @throws \Exception
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_generate_invoice()
     *      to controller's feature test
     */
    public function generateInvoice(GenerateInvoiceRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();
        $invoiceRepository = $this->quotationService->generateInvoice($quotation);
        return apiResponse($invoiceRepository, [
            'invoice' => new InvoiceResource($invoiceRepository->getModel())
        ]);
    }

    /**
     * Nullify a quotation
     *
     * @param NullifyRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_nullify_quotation()
     *      and Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_should_fail_when_delete_a_quotation_with_certain_statuses
     *      to controller's feature test
     */
    public function nullify(NullifyRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();
        $this->quotationRepository->setModel($quotation);
        $this->quotationRepository->nullify();

        return apiResponse($this->quotationRepository);
    }

    /**
     * Delete quotation
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_soft_delete_quotation()
     *      and Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_force_delete_quotation
     *      to controller's feature test
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();
        $this->quotationRepository->setModel($quotation);

        $force = $request->input('force');
        $this->quotationRepository->delete($force);

        return apiResponse($this->quotationRepository);
    }

    /**
     * Restore quotation
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_restore_quotation()
     *      to controller's feature test
     */
    public function restore(RestoreRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();

        $this->quotationRepository->setModel($quotation);
        $quotation = $this->quotationRepository->restore();

        return apiResponse($this->quotationRepository, ['quotation' => $quotation]);
    }

    /**
     * Remove signed doc
     *
     * @param DeleteSignedDocRequest $request
     * @return JsonResponse
     */
    public function removeSignedDoc(DeleteSignedDocRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();
        $this->quotationRepository->setModel($quotation);
        $this->quotationRepository->removeSignedDocument();
        return apiResponse($this->quotationRepository);
    }

    /**
     * Save signed doc
     *
     * @param SaveSignedDocRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_save_signed_document()
     *      To the controller method unit tester method.
     */
    public function saveSignedDoc(SaveSignedDocRequest $request): JsonResponse
    {
        $quotation = $request->getQuotation();
        $this->quotationRepository->setModel($quotation);
        $this->quotationRepository->saveSignedDocument($request->file('signed_document'));
        return apiResponse($this->quotationRepository);
    }

    /**
     * Quotation logs
     *
     * @param LogsPopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Quotation\QuotationTest::test_quotation_logs()
     *      To the controller method unit tester method.
     */
    public function quotationLogs(LogsPopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $logs = $this->quotationLogRepository->all($options, true);
        $logs = $this->quotationLogRepository->groupByDate();
        return response()->json(compact('logs'));
    }
}
