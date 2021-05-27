<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\WarrantyClaims\SaveWarrantyClaimRequest as SaveRequest;
use App\Http\Requests\WarrantyClaims\FindWarrantyClaimRequest as FindRequest;
use App\Http\Requests\WarrantyClaims\PopulateWarrantyClaimsRequest as PopulateRequest;

use App\Http\Resources\WarrantyClaimResource;

use App\Repositories\WarrantyClaimRepository;

class WarrantyClaimController extends Controller
{
    private $claim;

    public function __construct(WarrantyClaimRepository $claim)
    {
    	$this->claim = $claim;
    }

    public function warrantyClaims(PopulateRequest $request)
    {
    	$options = $request->options();

    	$claims = $this->claim->all($options);
    	$claims = $this->claim->paginate();
    	$claims->data = WarrantyClaimResource::collection($claims);

    	return response()->json(['warranty_claims' => $claims]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$claim = $this->claim->save($input);

    	return response()->json(['warranty_claim' => $claim]);
    }

    public function update(SaveRequest $request)
    {
    	$claim = $request->getWarrantyClaim();
    	$claim = $this->claim->setModel($claim);

    	$input = $request->onlyInRules();
    	$claim = $this->claim->save($input);

    	return apiResponse($this->claim, ['warranty_claim' => $claim]);
    }

    public function delete(FindRequest $request)
    {
    	$claim = $request->getWarrantyClaim();

    	$this->claim->setModel($claim);
    	$this->claim->delete();

    	return apiResponse($this->claim);
    }
}
