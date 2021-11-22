<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\PopulateWorklistAppointmentsRequest as PopulateRequest;
use App\Http\Requests\Worklists\Appointments\{
    AttachAppointmentRequest as AttachRequest,
    AttachManyAppointmentsRequest as AttachManyRequest,
    MoveAppointmentRequest as MoveRequest,
    DetachAppointmentRequest as DetachRequest,
    DetachManyAppointmentsRequest as DetachManyRequest,
    TruncateAppointmentsRequest as TruncateRequest
};
use App\Http\Resources\AppointmentResource;
use App\Repositories\{
    WorklistRepository, AppointmentRepository
};

class WorklistAppointmentController extends Controller
{
    /**
     * Worklist Repository Class Container
     * 
     * @var \App\Repositories\WorklistRepository
     */
    private $worklist;

    /**
     * Appointment Repository Class Container
     * 
     * @var \App\Repositories\AppointmentRepository
     */
    private $appointment;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\WorklistRepository  $worklist
     * @param \App\Repositories\AppointmentRepository  $appointment
     * @return void
     */
    public function __construct(WorklistRepository $worklist, AppointmentRepository $appointment)
    {
        $this->worklist = $worklist;
        $this->appointment = $appointment;
    }

    /**
     * Populate with worklist appointments
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function worklistAppointments(PopulateRequest $request)
    {
        $options = $request->options();

        $worklist = $request->getWorklist();

        $appointments = $worklist->appointments();
        $appointments = $this->appointment->setModel($appointments);
        $appointments = $this->appointment->all($options, true);
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Attach appointment to worklist
     * 
     * @param AttachRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attach(AttachRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointment = $request->getAppointment();
        $this->worklist->attachAppointment($appointment);

        return apiResponse($this->worklist);
    }

    /**
     * Attach many appointments to worklist
     * 
     * @param AttachManyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attachMany(AttachManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointmentIds = $request->appointment_ids;
        $this->worklist->attachManyAppointments($appointmentIds);

        return apiResponse($this->worklist);
    }

    /**
     * Move appointment to another worklist
     * 
     * @param MoveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function move(MoveRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->appointment->setModel($appointment);

        $worklist = $request->getWorklist();
        $this->appointment->moveTo($worklist);

        return apiResponse($this->worklist);
    }

    /**
     * Detach appointment from worklist
     * 
     * @param DetachRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detach(DetachRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointment = $request->getAppointment();
        $this->worklist->detachAppointment($appointment);

        return apiResponse($this->worklist);
    }

    /**
     * Detach many appointments from worklist
     * 
     * @param DetachManyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detachMany(DetachManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointmentIds = $request->appointment_ids;
        $this->worklist->detachManyAppointments($appointmentIds);

        return apiResponse($this->worklist);
    }

    /**
     * Truncate worklist appointments
     * 
     * @param TruncateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function truncate(TruncateRequest $request)
    {
        $worklist = $request->getWorklist();

        $this->worklist->setModel($worklist);
        $this->worklist->truncateAppointments();

        return apiResponse($this->worklist);
    }
}
