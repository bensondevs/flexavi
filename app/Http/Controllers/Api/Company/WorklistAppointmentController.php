<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\PopulateWorklistAppointmentsRequest as PopulateRequest;

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

        $appointments = $this->appointment->all($options, true);
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    public function attach(AttachRequest $request)
    {
        //
    }

    public function detach(DetachRequest $request)
    {
        //
    }
}
