<?php

namespace App\Http\Controllers\Api\Company\Warranty;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Warranties\FindWarrantyRequest as FindRequest;
use App\Http\Requests\Company\Warranties\PopulateEmployeeWarrantiesRequest as EmployeePopulateRequest;
use App\Http\Requests\Company\Warranties\PopulateWarrantiesRequest as PopulateRequest;
use App\Http\Requests\Company\Warranties\RestoreWarrantyRequest as RestoreRequest;
use App\Http\Requests\Company\Warranties\SaveWarrantyRequest as SaveRequest;
use App\Http\Requests\Company\Warranties\SetWarrantyStatusRequest as SetStatusRequest;
use App\Http\Requests\Warranties\{MultipleStoreRequest};
use App\Http\Resources\Warranty\WarrantyResource;
use App\Repositories\{Warranty\WarrantyAppointmentRepository,
    Warranty\WarrantyAppointmentWorkRepository,
    Warranty\WarrantyRepository};

class WarrantyController extends Controller
{
    /**
     * Warranty repository class container
     *
     * @var WarrantyRepository
     */
    private $warranty;

    /**
     * Warranty Appointment repository class container
     *
     * @var WarrantyAppointmentRepository
     */
    private $warrantyAppointment;

    /**
     * Warranty Appointment Work repository class container
     *
     * @var WarrantyAppointmentWorkRepository
     */
    private $warrantyAppointmentWork;

    /**
     * Controller constructor method
     *
     * @param WarrantyRepository $warranty
     * @param WarrantyAppointmentRepository $warrantyAppointment
     * @param WarrantyAppointmentWorkRepository $warrantyAppointmentWork
     * @return void
     */
    public function __construct(
        WarrantyRepository                $warranty,
        WarrantyAppointmentRepository     $warrantyAppointment,
        WarrantyAppointmentWorkRepository $warrantyAppointmentWork
    )
    {
        $this->warranty = $warranty;
        $this->warrantyAppointment = $warrantyAppointment;
        $this->warrantyAppointmentWork = $warrantyAppointmentWork;
    }

    /**
     * Populate company warranties
     *
     * @param PopulateRequest $request
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
     * Populate employee warranties
     *
     * @param EmployeePopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function employeeWarranties(EmployeePopulateRequest $request)
    {
        $warranties = $request->getEmployee()->warranties;
        $warranties = $request->getEmployee()->warranties;
        $warranties = $this->warranty->setCollection(collect($warranties));
        $warranties = $this->warranty->paginate();
        $warranties = WarrantyResource::apiCollection($warranties);

        return response()->json(['warranties' => $warranties]);
    }

    /**
     * Populate company warranties
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedWarranties(PopulateRequest $request)
    {
        $options = $request->options();

        $warranties = $this->warranty->trasheds($options, true);
        $warranties = WarrantyResource::apiCollection($warranties);

        return response()->json(['warranties' => $warranties]);
    }

    /**
     * Populate appointment warranties
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentWarranties(PopulateRequest $request)
    {
        $appointment = $request->getAppointment();
        $warranties = $appointment->warranties;
        $warranties = WarrantyResource::apiCollection($warranties);

        return response()->json(['warranties' => $warranties]);
    }

    /**
     * Store warranty
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $warranty = $this->warranty->save($request->warrantyData());

        foreach ($request->warrantyWorksData() as $warrantyWork) {
            $warrantyAppointment = $this->warrantyAppointment->save($warrantyWork, $warranty);
            foreach ($warrantyWork['works'] as $work) $this->warrantyAppointmentWork->save($work, $warrantyAppointment);
        }

        return apiResponse($this->warranty);
    }

    /**
     * View warranty
     *
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $warranty = $request->getWarranty();
        $warranty = new WarrantyResource($warranty);
        return response()->json(['warranty' => $warranty]);
    }

    /**
     * Set warranty status
     *
     * @param SetStatusRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function setStatus(SetStatusRequest $request)
    {
        $warranty = $request->getWarranty();
        $this->warranty->setModel($warranty);

        $status = $request->input('status');
        $applyToAllWorks = $request->input('apply_to_all_works', false);
        $this->warranty->setStatus($status, $applyToAllWorks);

        return apiResponse($this->warranty);
    }

    /**
     * Update warranty
     *
     * @param SaveRequest $request
     *
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
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(FindRequest $request)
    {
        $warranty = $request->getWarranty();
        $this->warranty->setModel($warranty);

        $force = $request->force ?? false;
        $this->warranty->delete($force);

        return apiResponse($this->warranty);
    }

    /**
     * Restore warranty
     *
     * @param RestoreRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $warranty = $request->getWarranty();

        $this->warranty->setModel($warranty);
        $warranty = $this->warranty->restore();

        return apiResponse($this->warranty, ['warranty' => $warranty]);
    }
}
