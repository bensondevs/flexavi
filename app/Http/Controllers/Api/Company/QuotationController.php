<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Quotations\SaveQuotationRequest as SaveRequest;
use App\Http\Requests\Quotations\FindQuotationRequest as FindRequest;
use App\Http\Requests\Quotations\CancelQuotationRequest as CancelRequest;
use App\Http\Requests\Quotations\ReviseQuotationRequest as ReviseRequest;
use App\Http\Requests\Quotations\HonorQuotationRequest as HonorRequest;
use App\Http\Requests\Quotations\PopulateCompanyQuotationRequest as PopulateRequest;

use App\Http\Resources\QuotationResource;

use App\Repositories\QuotationRepository;

class QuotationController extends Controller
{
    private $quotation;

    public function __construct(QuotationRepository $quotation)
    {
    	$this->quotation = $quotation;
    }

    public function companyQuotations(PopulateRequest $request)
    {
        $options = $request->options();

    	$quotations = $this->quotation->all($options);
        $quotations = $this->quotation->paginate();
        $quotations->data = QuotationResource::apiCollection($quotations);

    	return response()->json(['quotations' => $quotations]);
    }

    public function appoitnmentQuotations(AppointmentPopulateRequest $request)
    {
        $options = $request->options();

        $quotations = $this->quotation->all($options);
        $quotations = $this->quotation->paginate();
        $quotations->data = QuotationResource::apiCollection($quotations);

        return response()->json(['quotations' => $quotations]);
    }

    public function store(SaveRequest $request)
    {
        $documentUpload = $request->file('quotation_document');
        $quotation = $this->quotation->uploadDocument($documentUpload);

        $input = $request->quotationData();
        $input['creator_id'] = $request->user()->id;
    	$quotation = $this->quotation->save($input);

        activity()->causedBy($request->user())->performedOn($quotation)
            ->log($request->user()->fullname . ' has created quotation with ID: ' . $quotation->id);

    	return apiResponse($this->quotation);
    }

    public function reuploadDocument(SaveRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel();

        $documentUpload = $request->file('quotation_document');
        $quotation = $this->quotation->uploadDocument($documentUpload);

        activity()->causedBy($request->user())->performedOn($quotation)
            ->log($request->user()->fullname . ' has done reuploading document quotation with ID: ' . $quotation->id);

        return apiResponse($this->quotation);
    }

    public function revise(ReviseRequest $request)
    {
        $quotation = $request->getQuotation();
        $quotation = $this->quotation->setModel($quotation);

        $revisionData = $request->revisionData();
        $quotation = $this->quotation->revise($revisionData);

        activity()->causedBy($request->user())
            ->performedOn($quotation)
            ->log($request->user()->fullname . ' has revised quotation with ID: ' . $quotation->id . ' now the status of quotation is `Revised`');

        return apiResponse($this->quotation);
    }

    public function cancel(CancelRequest $request)
    {
        $quotation = $request->getQuotation();
        $this->quotation->setModel($quotation);

        $cancellationData = $request->cancellationData();
        $cancellationData['canceller'] = 'company';
        $quotation = $this->quotation->cancel($cancellationData);

        activity()->causedBy($request->user())
            ->performedOn($quotation)
            ->log($request->user()->fullname . ' has cancelled quotation with ID: ' . $quotation->id . ' now the status of quotation is `Cancelled`');

        return apiResponse($this->quotation);
    }

    public function honor(HonorRequest $request)
    {
        $quotation = $request->getQuotation();
        $this->quotation->setModel($quotation);

        $honorData = $request->honorData();
        $quotation = $this->quotation->honor($honorData);

        activity()->causedBy($request->user())
            ->performedOn($quotation)
            ->log($request->user()->fullname . ' has honored quotation with ID: ' . $quotation->id . ' now the status of quotation is `Honored`');

        return apiResponse($this->quotation);
    }

    public function update(SaveRequest $request)
    {
        $quotation = $request->getQuotation();
    	$quotation = $this->quotation->setModel($quotation);

        $input = $request->quotationData();
    	$quotation = $this->quotation->save($input);

        activity()->causedBy($request->user())
            ->performedOn($quotation)
            ->log($request->user()->fullname . ' has updated quotation with ID: ' . $quotation->id);

    	return apiResponse($this->quotation);
    }

    public function delete(DeleteRequest $request)
    {
        $quotation = $request->getQuotation();
    	$this->quotation->setModel($quotation);

        $force = $request->input('force');
    	$this->quotation->delete($force);

        activity()->causedBy($request->user())
            ->performedOn($quotation)
            ->log($request->user()->fullname . ' has' . ($force ? ' force ' : ' ') . 'deleted quotation with ID: ' . $quotation->id);

    	return apiResponse($this->quotation);
    }
}
