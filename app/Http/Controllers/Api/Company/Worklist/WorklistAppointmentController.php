<?php

namespace App\Http\Controllers\Api\Company\Worklist;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Appointments\PopulateWorklistAppointmentsRequest as PopulateRequest;
use App\Http\Requests\Company\Worklists\Appointments\{AttachAppointmentRequest as AttachRequest};
use App\Http\Requests\Company\Worklists\Appointments\AttachManyAppointmentsRequest as AttachManyRequest;
use App\Http\Requests\Company\Worklists\Appointments\DetachAppointmentRequest as DetachRequest;
use App\Http\Requests\Company\Worklists\Appointments\DetachManyAppointmentsRequest as DetachManyRequest;
use App\Http\Requests\Company\Worklists\Appointments\MoveAppointmentRequest as MoveRequest;
use App\Http\Requests\Company\Worklists\Appointments\TruncateAppointmentsRequest as TruncateRequest;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Repositories\{Appointment\AppointmentRepository, Worklist\WorklistRepository};

class WorklistAppointmentController extends Controller
{
    /**
     * Worklist Repository Class Container
     *
     * @var WorklistRepository
     */
    private $worklist;

    /**
     * Appointment Repository Class Container
     *
     * @var AppointmentRepository
     */
    private $appointment;

    /**
     * Controller constructor method
     *
     * @param WorklistRepository $worklist
     * @param AppointmentRepository $appointment
     * @return void
     */
    public function __construct(
        WorklistRepository    $worklist,
        AppointmentRepository $appointment
    )
    {
        $this->worklist = $worklist;
        $this->appointment = $appointment;
    }

    /**
     * Populate with worklist appointments
     *
     * @param PopulateRequest $request
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
     * @param AttachRequest $request
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
     * @param AttachManyRequest $request
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
     * @param MoveRequest $request
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
     * @param DetachRequest $request
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
     * @param DetachManyRequest $request
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
     * @param TruncateRequest $request
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
