<?php

namespace App\Http\Controllers\Api\Company\Warranties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Warranties\{
    SaveWarrantyRequest as SaveRequest,
    FindWarrantyRequest as FindRequest,
    PopulateWarrantiesRequest as PopulateRequest
};
use App\Http\Resources\WarrantyResource;
use App\Repositories\WarrantyRepository;

class WarrantyController extends Controller
{
    /**
     * Warranty repository class container
     * 
     * @var \App\Repositories\WarrantyRepository
     */
    private $warranty;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\WarrantyRepository  $warranty
     * @return void
     */
    public function __construct(WarrantyRepository $warranty)
    {
    	$this->warranty = $warranty;
    }

    /**
     * Populate company warranties
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyWarranties(PopulateRequest $request)
    {
    	$options = $request->options();

    	$warranties = $this->warranty->all($options, true);
    	$warranties = WarrantyResource::apiCollection($warranties);

    	return response()->json(['warranties' => $warranties]);
    }

    /**
     * Populate appointment warranties
     * 
     * @param  AppointmentPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentWarranties()
    {
        $appointment = $request->getAppointment();
        $warranties = $appointment->warranties;
        $warranties = WarrantyResource::apiCollection($warranties);

        return response()->json(['warranties' => $warranties]);
    }

    /**
     * Store warranty
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $warranty = $this->warranty->save($input);

        if ($request->has('warranty_works')) {
            $this->warranty->save($input['warranty_works']);
        }

        return apiResponse($this->warranty);
    }
    
    /**
     * View warranty
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $warranty = $request->getWarranty();

        $relations = $request->relations();
        $warranty->load($relations);

        $warranty = new WarrantyResource($warranty);
        return response()->json(['warranty' => $warranty]);
    }

    /**
     * Update warranty
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $warranty = $request->getWarranty();
        $this->warranty->setModel($warranty);

        $input = $request->validated();
        $this->warranty->save($input);

        return apiResponse($this->warranty);
    }

    /**
     * Delete warranty
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(FindRequest $request)
    {
    	$warranty = $request->getWarranty();
    	$this->warranty->setModel($warranty);

        $force = $request->force;
    	$this->warranty->delete($force);

    	return apiResponse($this->warranty);
    }
}