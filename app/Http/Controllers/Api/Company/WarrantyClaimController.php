<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\WarrantyClaims\{
    SaveWarrantyClaimRequest as SaveRequest,
    FindWarrantyClaimRequest as FindRequest,
    PopulateWarrantyClaimsRequest as PopulateRequest
};
use App\Http\Resources\WarrantyClaimResource;
use App\Repositories\WarrantyClaimRepository;

class WarrantyClaimController extends Controller
{
    /**
     * Warranty claim repository class container
     * 
     * @var \App\Repositories\WarrantyClaimRepository
     */
    private $claim;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\WarrantyClaimRepository  $claim
     * @return void
     */
    public function __construct(WarrantyClaimRepository $claim)
    {
    	$this->claim = $claim;
    }

    /**
     * Populate warranty claims
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function warrantyClaims(PopulateRequest $request)
    {
    	$options = $request->options();

    	$claims = $this->claim->all($options, true);
    	$claims = WarrantyClaimResource::apiCollection($claims);

    	return response()->json(['warranty_claims' => $claims]);
    }

    /**
     * Store warranty claim
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$claim = $this->claim->save($input);

    	return response()->json(['warranty_claim' => $claim]);
    }

    /**
     * Update warranty claim
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
    	$claim = $request->getWarrantyClaim();
    	$claim = $this->claim->setModel($claim);

    	$input = $request->onlyInRules();
    	$claim = $this->claim->save($input);

    	return apiResponse($this->claim, ['warranty_claim' => $claim]);
    }

    /**
     * Delete warranty claim
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(FindRequest $request)
    {
    	$claim = $request->getWarrantyClaim();

    	$this->claim->setModel($claim);
    	$this->claim->delete();

    	return apiResponse($this->claim);
    }
}
