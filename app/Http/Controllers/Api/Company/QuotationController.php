<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Quotations\SaveQuotationRequest as SaveRequest;
use App\Http\Requests\Quotations\FindQuotationRequest as FindRequest;
use App\Http\Requests\Quotations\SendQuotationRequest as SendRequest;
use App\Http\Requests\Quotations\PrintQuotationRequest as PrintRequest;
use App\Http\Requests\Quotations\ReviseQuotationRequest as ReviseRequest;
use App\Http\Requests\Quotations\HonorQuotationRequest as HonorRequest;
use App\Http\Requests\Quotations\DeleteQuotationRequest as DeleteRequest;
use App\Http\Requests\Quotations\CancelQuotationRequest as CancelRequest;
use App\Http\Requests\Quotations\PopulateCompanyQuotationsRequest as CompanyPopulateRequest;
use App\Http\Requests\Quotations\PopulateCustomerQuotationsRequest as CustomerPopulateRequest;
use App\Http\Requests\Quotations\AddQuotationAttachmentRequest as AddAttachmentRequest;
use App\Http\Requests\Quotations\RemoveQuotationAttachmentRequest as RemoveAttachmentRequest;

use App\Enums\Quotation\QuotationCanceller;

use App\Http\Resources\QuotationResource;
use App\Http\Resources\QuotationAttachmentResource;

use App\Repositories\QuotationRepository;

class QuotationController extends Controller
{
    private $quotation;

    public function __construct(QuotationRepository $quotation)
    {
    	$this->quotation = $quotation;
    }

    public function companyQuotations(CompanyPopulateRequest $request)
    {
        $options = $request->options();

    	$quotations = $this->quotation->all($options);
        $quotations = $this->quotation->paginate();
        $quotations = QuotationResource::apiCollection($quotations);

    	return response()->json(['quotations' => $quotations]);
    }

    public function customerQuotations(CustomerPopulateRequest $request)
    {
        $options = $request->options();

        $quotations = $this->quotation->all($options);
        $quotations = $this->quotation->paginate();
        $quotations = QuotationResource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    public function trashedQuotations(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $quotations = $this->quotation->trasheds($options);
        $quotations = $this->quotation->paginate();
        $quotations = QuotationResource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->quotationData();
    	$quotation = $this->quotation->save($input);

    	return apiResponse($this->quotation);
    }

    public function view(FindRequest $request)
    {
        $quotation = $request->getQuotation();
        $relations = $request->relations();
        $quotation->load($relations);
        $quotaton = new QuotationResource($quotation);

        return response()->json(['quotation' => $quotation]);
    }

    public function attachments(FindRequest $request)
    {
        $quotation = $request->getQuotation();
        $attachments = $quotation->attachments;
        $attachments = QuotationAttachmentResource::collection($attachments); 

        return response()->json(['attachments' => $attachments]);
    }

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

    public function removeAttachment(RemoveAttachmentRequest $request)
    {
        $attachment = $request->getQuotationAttachment();

        activity()->causedBy($request->user())
            ->performedOn($attachment)
            ->log($request->user()->fullname . ' has removed quotation attachment with ID: ' . $attachment->id);

        $this->quotation->removeAttachment($attachment);

        return apiResponse($this->quotation);
    }

    public function send(SendRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $sendData = $request->sendData();
        $quotation = $this->quotation->send($sendData);

        return apiResponse($this->quotation);
    }

    public function print(PrintRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);
        $quotation = $this->quotation->print();

        return apiResponse($this->quotation);
    }

    public function revise(ReviseRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $revisionData = $request->revisionData();
        $quotation = $this->quotation->revise($revisionData);

        return apiResponse($this->quotation);
    }

    public function cancel(CancelRequest $request)
    {
        $quotation = $request->getQuotation();
        $this->quotation->setModel($quotation);

        $cancellationData = $request->cancellationData();
        $cancellationData['canceller'] = QuotationCanceller::Company;
        $quotation = $this->quotation->cancel($cancellationData);

        return apiResponse($this->quotation);
    }

    public function honor(HonorRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $honorData = $request->honorData();
        $quotation = $this->quotation->honor($honorData);

        return apiResponse($this->quotation);
    }

    public function generateInvoice(GenerateInvoiceRequest $request)
    {
        $quotation = $this->getQuotation();
        $invoice = $this->invoice->generateFromQuotation($quotation);

        return apiResponse($this->invoice);
    }

    public function update(SaveRequest $request)
    {
        $quotation = $request->getQuotation();
    	$quotation = $this->quotation->setModel($quotation);

        $input = $request->quotationData();
    	$quotation = $this->quotation->save($input);

    	return apiResponse($this->quotation);
    }

    public function delete(DeleteRequest $request)
    {
        $quotation = $request->getQuotation();
    	$this->quotation->setModel($quotation);

        $force = $request->input('force');
    	$this->quotation->delete($force);

    	return apiResponse($this->quotation);
    }
}
