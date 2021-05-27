<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Warranties\SaveWarrantyRequest as SaveRequest;
use App\Http\Requests\Warranties\FindWarrantyRequest as FindRequest;
use App\Http\Requests\Warranties\PopulateWarrantiesRequest as PopulateRequest;

use App\Http\Resources\WarrantyResource;

use App\Repositories\WarrantyRepository;

class WarrantyController extends Controller
{
    private $warranty;

    public function __construct(WarrantyRepository $warranty)
    {
    	$this->warranty = $warranty;
    }

    public function warranties(PopulateRequest $request)
    {
    	$options = $request->options();

    	$warranties = $this->warranty->all($options);
    	$warranties = $this->warranty->paginate();
    	$warranties->data = WarrantyResource::collection($warranties);

    	return response()->json(['warranties' => $warranties]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$warranty = $this->warranty->save($input);

    	return apiResponse($this->warranty, ['warranty' => $warranty]);
    }

    public function update(SaveRequest $request)
    {
    	$warranty = $request->getWarranty();
    	$this->warranty->setModel($warranty);

    	$input = $request->onlyInRules();
    	$this->warranty->save($input);

    	return apiResponse($this->warranty, ['warranty' => $warranty]);
    }

    public function delete(FindRequest $request)
    {
    	$warranty = $request->getWarranty();
    	
    	$this->warranty->setModel($warranty);
    	$this->warranty->delete();

    	return apiResponse($this->warranty);
    }
}