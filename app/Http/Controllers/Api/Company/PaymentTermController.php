<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\PaymentTerms\CreatePaymentTermRequest as CreateRequest;
use App\Http\Requests\PaymentTerms\UpdatePaymentTermRequest as UpdateRequest;
use App\Http\Requests\PaymentTerms\FindPaymentTermRequest as FindRequest;
use App\Http\Requests\PaymentTerms\PopulatePaymentTermsRequest as PopulateRequest;

use App\Http\Resources\PaymentTermResource;

use App\Repositories\PaymentTermRepository;

class PaymentTermController extends Controller
{
    private $term;

    public function __construct(PaymentTermRepository $term)
    {
    	$this->term = $term;
    }

    public function paymentTerms(PopulateRequest $request)
    {
    	$options = $request->options();

    	$terms = $this->term->all($options, true);
    	$terms = PaymentTermResource::apiCollection($terms);

    	return response()->json(['payment_terms' => $terms]);
    }

    public function store(CreateRequest $request)
    {
        $input = $request->validated();
    	$this->term->save($input);

    	return apiResponse($this->term);
    }

    public function update(UpdateRequest $request)
    {
        $term = $request->getPaymentTerm();
        $this->term->setModel($term);

        $input = $request->validated();
        $this->term->save($input);

        return apiResponse($this->term);
    }

    public function markAsPaid(FindRequest $request)
    {
        $term = $request->getPaymentTerm();

        $this->term->setModel($term);
        $this->term->markAsPaid();

        return apiResponse($this->term);
    }

    public function cancelPaidStatus(FindRequest $request)
    {
        $term = $request->getPaymentTerm();
        $term = $this->term->setModel($term);

        $reason = $request->input('reason');
        $term = $this->term->cancelPaidStatus();

        return apiResponse($this->term);
    }

    public function forwardToDebtCollector(FindRequest $request)
    {
        $term = $request->getPaymentTerm();

        $this->term->setModel($term);
        $this->term->forwardToDebtCollector();
    
        return apiResponse($this->term);
    }

    public function delete(FindRequest $request)
    {
    	$term = $request->getPaymentTerm();
        
    	$this->term->setModel($term);
    	$this->term->delete();

    	return apiResponse($this->term);
    }
}
