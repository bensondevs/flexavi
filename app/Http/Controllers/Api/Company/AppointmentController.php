<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

use App\Http\Requests\Appointments\SaveAppointmentRequest as SaveRequest;
use App\Http\Requests\Appointments\FindAppointmentRequest as FindRequest;
use App\Http\Requests\Appointments\AssignAppointmentTypeRequest as AssignTypeRequest;
use App\Http\Requests\Appointments\PopulateCompanyAppointmentsRequest as PopulateRequest;

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

    public function companyAppointments(PopulateRequest $request)
    {
        $options = $request->options();
    
        $appointments = $this->appointment->all($options);
        $appointments = $this->appointment->paginate($options['per_page']);
        $appointments = AppointmentResource::apiCollection($appointments);

    	return response()->json(['appointments' => $appointments]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
        $appointment = $this->appointment->save($input);

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }

    public function assignType(AssignTypeRequest $requesr)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $input = $request->ruleWithCompany();
        $appointment = $this->appointment->assignType($input);

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }

    public function update(SaveRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $input = $request->ruleWithCompany();
        $appointment = $this->appointment->save($input);

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }

    public function delete(FindRequest $request)
    {
        $appointment = $request->getAppointment();

        $this->appointment->setModel($appointment);
        $this->appointment->delete();

        return apiResponse($this->appointment);
    }
}
