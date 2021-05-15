<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    	$appointments = $this->appointment->companyAppointments(
    		$request->getCompany()
    	);
        $appointments = $this->appointment->paginate();
        $appointments->data = AppoiintmentResource::collection($appointments);

    	return response()->json(['appointments' => $appointments]);
    }

    public function store(SaveAppointmentRequest $request)
    {
        $appointment = $this->appointment->save(
            $request->onlyInRules()
        );

        return apiResponse($this->appointment, $appointment);
    }

    public function assignType(AssignAppointmentTypeRequest $requesr)
    {
        $this->appointment->setModel($request->getAppointment());
        $appointment = $this->appointment->assignType(
            $request->onlyInRules()
        );

        return apiResponse($this->appointment, $appointment);
    }

    public function update(SaveAppointmentRequest $request)
    {
        $this->appointment->setModel(
            $request->getAppointment()
        );
        $appointment = $this->appointment->save(
            $request->onlyInRules()
        );

        return apiResponse($this->appointment, $appointment);
    }

    public function delete(Request $request)
    {
        $this->appointment->find($request->id);
        $this->appointment->delete();

        return apiResponse($this->appointment);
    }
}
