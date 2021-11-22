<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\SubAppointments\{
    SaveSubAppointmentRequest as SaveRequest,
    PopulateSubAppointmentsRequest as PopulateRequest,
    CancelSubAppointmentRequest as CancelRequest,
    RescheduleSubAppointmentRequest as RescheduleRequest,
    ExecuteSubAppointmentRequest as ExecuteRequest,
    ProcessSubAppointmentRequest as ProcessRequest,
    DeleteSubAppointmentRequest as DeleteRequest
};

use App\Http\Resources\SubAppointmentResource;

use App\Repositories\SubAppointmentRepository;

class SubAppointmentController extends Controller
{
    /**
     * Sub Appointment Repository Class Container
     * 
     * @var \App\Repository\SubAppointmentRepository
     */
    private $subAppointment;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\SubAppointmentRepository  $subAppointment
     * @return void
     */
    public function __construct(SubAppointmentRepository $subAppointment)
    {
        $this->subAppointment = $subAppointment;
    }

    /**
     * Populate Appointment Subs
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function subAppointments(PopulateRequest $request)
    {
        $options = $request->options();

        $subAppointments = $this->subAppointment->all($options);
        $subAppointments = SubAppointmentResource::collection($subAppointments);

        return response()->json(['sub_appointments' => $subAppointments]);
    }

    /**
     * Store sub appointment
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $subAppointment = $this->subAppointment->save($input);

        return apiResponse($this->subAppointment);
    }

    /**
     * Update sub appointment
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $subAppointment = $this->subAppointment->setModel($subAppointment);

        $input = $request->validated();
        $subAppointment = $this->subAppointment->save($input);

        return apiResponse($this->subAppointment);
    }

    /**
     * Cancel sub appointment
     * 
     * @param CancelRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function cancel(CancelRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $subAppointment = $this->subAppointment->setModel($subAppointment);

        $input = $request->validated();
        $subAppointment = $this->subAppointment->cancel($input);

        return apiResponse($this->subAppointment);
    }

    /**
     * Reschedle sub appointment
     * 
     * @param RescheduleRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function reschedule(RescheduleRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $subAppointment = $this->subAppointment->setModel($subAppointment);

        $newSchedule = $request->validated();
        $subAppointment = $this->subAppointment->reschedule($newSchedule);

        return apiResponse($this->subAppointment);
    }

    /**
     * Execute sub appointment
     * 
     * @param ExecuteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function execute(ExecuteRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        
        $this->subAppointment->setModel($subAppointment);
        $this->subAppointment->execute();

        return apiResponse($this->subAppointment);
    }

    /**
     * Process sub appointment
     * 
     * @param ProcessRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function process(ProcessRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        
        $this->subAppointment->setModel($subAppointment);
        $this->subAppointment->process();

        return apiResponse($this->subAppointment);
    }

    /**
     * Delete sub appointment
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $subAppointment = $request->getSubAppointment();

        $this->subAppointment->setModel($subAppointment);
        $this->subAppointment->delete();

        return apiResponse($this->subAppointment);
    }
}
