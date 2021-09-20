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

    public function companyWarranties(PopulateRequest $request)
    {
    	$options = $request->options();

    	$warranties = $this->warranty->all($options, true);
    	$warranties = WarrantyResource::apiCollection($warranties);

    	return response()->json(['warranties' => $warranties]);
    }

    public function multipleStore(MultipleStoreRequest $request)
    {
        $rawWarranties = $request->collectRawWarranties();
        $this->warranty->storeMultiple($rawWarranties);

        $appointment = $request->getAppointment();
        return response()->json([
            'appointment' => $appointment->fresh(),
            'warranties' => $appointment->warranties,
        ]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $warranty = $this->warranty->save($input);

        return apiResponse($this->warranty);
    }

    public function update(SaveRequest $request)
    {
        $warranty = $request->getWarranty();
        $this->warranty->setModel($warranty);

        $input = $request->validated();
        $this->warranty->save($input);

        return apiResponse($this->warranty);
    }

    public function view(FindRequest $request)
    {
        $warranty = $request->getWarranty();

        $relations = $request->relations();
        $warranty->load($relations);

        $warranty = new WarrantyResource($warranty);
        return response()->json(['warranty' => $warranty]);
    }

    public function delete(FindRequest $request)
    {
    	$warranty = $request->getWarranty();
    	$this->warranty->setModel($warranty);

        $force = $request->force;
    	$this->warranty->delete($force);

    	return apiResponse($this->warranty);
    }
}