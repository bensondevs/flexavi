<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\PaymentTerms\{
    CreatePaymentTermRequest as CreateRequest,
    UpdatePaymentTermRequest as UpdateRequest,
    FindPaymentTermRequest as FindRequest,
    PopulatePaymentTermsRequest as PopulateRequest,
    CancelPaymentTermPaidStatusRequest as CancelPaidStatusRequest
};

use App\Http\Resources\PaymentTermResource;

use App\Repositories\PaymentTermRepository;

class PaymentTermController extends Controller
{
    /**
     * Payment Term Repository Class Container
     * 
     * @var \App\Repositories\PaymentTermRepository
     */
    private $term;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\PaymentTermRepository  $term
     * @return void
     */
    public function __construct(PaymentTermRepository $term)
    {
    	$this->term = $term;
    }

    /**
     * Populate company payment terms
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function paymentTerms(PopulateRequest $request)
    {
    	$options = $request->options();

    	$terms = $this->term->all($options, true);
    	$terms = PaymentTermResource::apiCollection($terms);

    	return response()->json(['payment_terms' => $terms]);
    }

    /**
     * Store company payment term
     * 
     * @param CreateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(CreateRequest $request)
    {
        $input = $request->validated();
    	$this->term->save($input);

    	return apiResponse($this->term);
    }

    /**
     * Update company payment term
     * 
     * @param UpdateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(UpdateRequest $request)
    {
        $term = $request->getPaymentTerm();
        $this->term->setModel($term);

        $input = $request->validated();
        $this->term->save($input);

        return apiResponse($this->term);
    }

    /**
     * Mark payment term as paid
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function markAsPaid(FindRequest $request)
    {
        $term = $request->getPaymentTerm();

        $this->term->setModel($term);
        $this->term->markAsPaid();

        return apiResponse($this->term);
    }

    /**
     * Register Payment Term
     */
    public function registerPayment()
    {
        //
    }

    /**
     * Cancel payment paid status
     * 
     * @param CancelPaidStatusRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function cancelPaidStatus(CancelPaidStatusRequest $request)
    {
        $term = $request->getPaymentTerm();
        $term = $this->term->setModel($term);

        $reason = $request->input('reason');
        $term = $this->term->cancelPaidStatus();

        return apiResponse($this->term);
    }

    /**
     * Forward to debt collector
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function forwardToDebtCollector(FindRequest $request)
    {
        $term = $request->getPaymentTerm();

        $this->term->setModel($term);
        $this->term->forwardToDebtCollector();
    
        return apiResponse($this->term);
    }

    /**
     * Delete payment term
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(FindRequest $request)
    {
    	$term = $request->getPaymentTerm();
        
    	$this->term->setModel($term);
    	$this->term->delete();

    	return apiResponse($this->term);
    }
}
