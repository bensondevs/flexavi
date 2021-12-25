<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Quotations\{
    SaveQuotationRequest as SaveRequest,
    FindQuotationRequest as FindRequest,
    SendQuotationRequest as SendRequest,
    PrintQuotationRequest as PrintRequest,
    ReviseQuotationRequest as ReviseRequest,
    HonorQuotationRequest as HonorRequest,
    DeleteQuotationRequest as DeleteRequest,
    CancelQuotationRequest as CancelRequest,
    RestoreQuotationRequest as RestoreRequest,
    GenerateQuotationInvoiceRequest as GenerateInvoiceRequest,
    PopulateCompanyQuotationsRequest as CompanyPopulateRequest,
    PopulateCustomerQuotationsRequest as CustomerPopulateRequest,
    AddQuotationAttachmentRequest as AddAttachmentRequest,
    RemoveQuotationAttachmentRequest as RemoveAttachmentRequest
};

use App\Enums\Quotation\QuotationCanceller;

use App\Http\Resources\{
    InvoiceResource,
    QuotationResource as Resource,
    QuotationAttachmentResource as AttachmentResource
};

use App\Repositories\{
    QuotationRepository,
    InvoiceRepository
};

class QuotationController extends Controller
{
    /**
     * Quotation Repository Class Container
     * 
     * @var \App\Repositories\QuotationRepository
     */
    private $quotation;

    /**
     * Invoice Repository Class Container
     * 
     * @var \App\Repositories\InvoiceRepository
     */
    private $invoice;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\QuotationRepository  $quotation
     * @param \App\Repositories\InvoiceRepository  $invoice
     * @return void
     */
    public function __construct(
        QuotationRepository $quotation, 
        InvoiceRepository $invoice
    ) {
    	$this->quotation = $quotation;
        $this->invoice = $invoice;
    }

    /**
     * Populate company quotations
     * 
     * @param CompanyPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyQuotations(CompanyPopulateRequest $request)
    {
        $options = $request->options();

    	$quotations = $this->quotation->all($options);
        $quotations = $this->quotation->paginate();
        $quotations = Resource::apiCollection($quotations);

    	return response()->json(['quotations' => $quotations]);
    }

    /**
     * Populate customer quotations
     * 
     * @param CustomerPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function customerQuotations(CustomerPopulateRequest $request)
    {
        $options = $request->options();

        $quotations = $this->quotation->all($options);
        $quotations = $this->quotation->paginate();
        $quotations = Resource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    /**
     * Populate trashed quotations
     * 
     * @param CompanyPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedQuotations(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $quotations = $this->quotation->trasheds($options);
        $quotations = $this->quotation->paginate();
        $quotations = Resource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    /**
     * Store company quoation
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->quotationData();
    	$quotation = $this->quotation->save($input);

    	return apiResponse($this->quotation);
    }

    /**
     * View company quotation
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotaton = new Resource($quotation);

        return response()->json(['quotation' => $quotation]);
    }

    /**
     * Populate quotation attachments
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attachments(FindRequest $request)
    {
        $quotation = $request->getQuotation();
        $attachments = $quotation->attachments;
        $attachments = AttachmentResource::collection($attachments); 

        return response()->json(['attachments' => $attachments]);
    }

    /**
     * Add quotation attachment
     * 
     * @param AddAttachmentRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function addAttachment(AddAttachmentRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $attachmentData = $request->validated();
        $quotation = $this->quotation->addAttachment($attachmentData);

        activity()->causedBy($request->user())->performedOn($quotation)
            ->log($request->user()->fullname . ' has attached a document to a quotation with ID: ' . $quotation->id);

        return apiResponse($this->quotation);
    }

    /**
     * Remove quotation attachment
     * 
     * @param RemoveAttachmentRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function removeAttachment(RemoveAttachmentRequest $request)
    {
        $attachment = $request->getQuotationAttachment();

        activity()->causedBy($request->user())
            ->performedOn($attachment)
            ->log($request->user()->fullname . ' has removed quotation attachment with ID: ' . $attachment->id);

        $this->quotation->removeAttachment($attachment);

        return apiResponse($this->quotation);
    }

    /**
     * Send quotation
     * 
     * @param SendRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function send(SendRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $sendData = $request->sendData();
        $quotation = $this->quotation->send($sendData);

        return apiResponse($this->quotation);
    }

    /**
     * Print quotation
     * 
     * @param PrintRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function print(PrintRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);
        $quotation = $this->quotation->print();

        return apiResponse($this->quotation);
    }

    /**
     * Revise quotation
     * 
     * @param ReviseRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function revise(ReviseRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $revisionData = $request->revisionData();
        $quotation = $this->quotation->revise($revisionData);

        return apiResponse($this->quotation);
    }

    /**
     * Cancel quotation
     * 
     * @param CancelRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function cancel(CancelRequest $request)
    {
        $quotation = $request->getQuotation();
        $this->quotation->setModel($quotation);

        $cancellationData = $request->cancellationData();
        $cancellationData['canceller'] = QuotationCanceller::Company;
        $quotation = $this->quotation->cancel($cancellationData);

        return apiResponse($this->quotation);
    }

    /**
     * Honor quotation
     * 
     * @param HonorRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function honor(HonorRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $honorData = $request->honorData();
        $quotation = $this->quotation->honor($honorData);

        return apiResponse($this->quotation);
    }

    /**
     * Generate invoice from quotation
     * 
     * @param GenerateInvoiceRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function generateInvoice(GenerateInvoiceRequest $request)
    {
        $quotation = $request->getQuotation();
        $invoiceData = $request->validated();

        $invoice = $this->invoice->generateFromQuotation($quotation, $invoiceData);
        $invoice->load(['items', 'invoiceable']);
        $invoice = new InvoiceResource($invoice);

        return apiResponse($this->invoice, ['invoice' => $invoice]);
    }

    /**
     * Update quotation
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $quotation = $request->getQuotation();
    	$quotation = $this->quotation->setModel($quotation);

        $input = $request->quotationData();
    	$quotation = $this->quotation->save($input);

    	return apiResponse($this->quotation);
    }

    /**
     * Delete quotation
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $quotation = $request->getQuotation();
    	$this->quotation->setModel($quotation);

        $force = $request->input('force');
    	$this->quotation->delete($force);

    	return apiResponse($this->quotation);
    }

    /**
     * Restore quotation
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $quotation = $request->getQuotation();
        
        $this->quotation->setModel($quotation);
        $quotation = $this->quotation->restore();
        
        return apiResponse($this->quotation, ['quotation' => $quotation]);
    }
}
