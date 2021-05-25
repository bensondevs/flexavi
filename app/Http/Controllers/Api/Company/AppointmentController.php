<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

use App\Http\Requests\Appointments\SaveAppointmentRequest;
use App\Http\Requests\Appointments\FindAppointmentRequest;
use App\Http\Requests\Appointments\AssignAppointmentTypeRequest;
use App\Http\Requests\Appointments\PopulateCompanyAppointmentsRequest;

use App\Http\Resources\AppointmentResource;

use App\Repositories\AppointmentRepository;

class AppointmentController extends Controller
{
    private $appointment;

    public function __construct(
    	AppointmentRepository $appointment
    )
    {
    	$this->appointment = $appointment;
    }

    public function companyAppointments(
    	PopulateCompanyAppointmentsRequest $request
    )
    {
        $options = $request->options();
    
        $appointments = $this->appointment->all($options);
        $appointments = $this->appointment->paginate();
        $appointments->data = AppointmentResource::collection($appointments);

    	return response()->json(['appointments' => $appointments]);
    }

    public function store(SaveAppointmentRequest $request)
    {
        $appointment = $this->appointment->save(
            $request->ruleWithCompany()
        );

        return apiResponse($this->appointment, $appointment);
    }

    public function assignType(AssignAppointmentTypeRequest $requesr)
    {
        $this->appointment->setModel($request->getAppointment());
        $appointment = $this->appointment->assignType(
            $request->ruleWithCompany()
        );

        return apiResponse($this->appointment, $appointment);
    }

    public function update(SaveAppointmentRequest $request)
    {
        $this->appointment->setModel(
            $request->getAppointment()
        );
        $appointment = $this->appointment->save(
            $request->ruleWithCompany()
        );

        return apiResponse($this->appointment, $appointment);
    }

    public function delete(FindAppointmentRequest $request)
    {
        $this->appointment->setModel($request->getAppointment());
        $this->appointment->delete();

        return apiResponse($this->appointment);
    }
}
