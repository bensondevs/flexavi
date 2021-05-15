<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\PopulateQuotationRequest;
use App\Http\Requests\SaveQuotationRequest;

use App\Http\Resources\QuotationResource;

use App\Repositories\QuotationRepository;

class QuotationController extends Controller
{
    private $quotation;

    public function __construct(QuotationRepository $quotation)
    {
    	$this->quotation = $quotation;
    }

    public function companyQutations(PopulateQuotationRequest $request)
    {
    	$quotations = $this->quotation->all($request->options());
        $quotations = $this->quotation->paginate();
        $quotations->data = QuotationResource::collection($quotations);

    	return response()->json(['quotations' => $quotations]);
    }

    public function store(SaveQuotationRequest $request)
    {
        $input = $request->onlyInRules();
    	$quotation = $this->quotation->save($input);

    	return apiResponse($this->quotation, $quotation);
    }

    public function update(SaveQuotationRequest $request)
    {
    	$this->quotation->setModel($request->getQuotation());
    	$quotation = $this->quotation->save($request->onlyInRules());

    	return apiResponse($this->quotation, $quotation);
    }

    public function delete(Request $request)
    {
    	$this->quotation->find($request->input('id'));
    	$this->quotation->delete();

    	return apiResponse($this->quotation);
    }
}
