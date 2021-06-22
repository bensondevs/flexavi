<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Quotations\SaveQuotationRequest as SaveRequest;
use App\Http\Requests\Quotations\FindQuotationRequest as FindRequest;
use App\Http\Requests\Quotations\ReviseQuotationRequest as ReviseRequest;
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
        $quotations->data = QuotationResource::collection($quotations);

    	return response()->json(['quotations' => $quotations]);
    }

    public function store(SaveRequest $request)
    {
        $documentUpload = $request->file('quotation_document');
        $quotation = $this->quotation->uploadDocument($documentUpload);

        $input = $request->quotationData();
        $input['creator_id'] = $request->user()->id;
    	$quotation = $this->quotation->save($input);

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
        $cancellationData['canceller'] = 'company';
        $this->quotation->cancel($cancellationData);

        return apiResponse($this->quotation);
    }

    public function update(SaveRequest $request)
    {
        $quotation = $request->getQuotation();
    	$quotation = $this->quotation->setModel($quotation);

        $input = $request->quotationData();
    	$quotation = $this->quotation->save($input);

    	return apiResponse($this->quotation);
    }

    public function delete(FindRequest $request)
    {
        $quotation = $request->getQuotation();

    	$this->quotation->setModel($quotation);
    	$this->quotation->delete();

    	return apiResponse($this->quotation);
    }
}
