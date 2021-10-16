<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\PopulateWorklistAppointmentsRequest as PopulateRequest;
use App\Http\Requests\Worklists\Appointments\AttachAppointmentRequest as AttachRequest;
use App\Http\Requests\Worklists\Appointments\AttachManyAppointmentsRequest as AttachManyRequest;
use App\Http\Requests\Worklists\Appointments\DetachAppointmentRequest as DetachRequest;
use App\Http\Requests\Worklists\Appointments\DetachManyAppointmentsRequest as DetachManyRequest;
use App\Http\Requests\Worklists\Appointments\TruncateAppointmentsRequest as TruncateRequest;

use App\Http\Resources\AppointmentResource;

use App\Repositories\WorklistRepository;
use App\Repositories\AppointmentRepository;

class WorklistAppointmentController extends Controller
{
    private $worklist;
    private $appointment;

    public function __construct(WorklistRepository $worklist, AppointmentRepository $appointment)
    {
        $this->worklist = $worklist;
        $this->appointment = $appointment;
    }

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

    public function attach(AttachRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointment = $request->getAppointment();
        $this->worklist->attachAppointment($appointment);

        return apiResponse($this->worklist);
    }

    public function attachMany(AttachManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointmentIds = $request->appointment_ids;
        $this->worklist->attachManyAppointments($appointmentIds);

        return apiResponse($this->worklist);
    }

    public function detach(DetachRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointment = $request->getAppointment();
        $this->worklist->detachAppointment($appointment);

        return apiResponse($this->worklist);
    }

    public function detachMany(DetachManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $appointmentIds = $request->appointment_ids;
        $this->worklist->detachManyAppointments($appointmentIds);

        return apiResponse($this->worklist);
    }

    public function truncate(TruncateRequest $request)
    {
        $worklist = $request->getWorklist();

        $this->worklist->setModel($worklist);
        $this->worklist->truncateAppointments();

        return apiResponse($this->worklist);
    }
}
