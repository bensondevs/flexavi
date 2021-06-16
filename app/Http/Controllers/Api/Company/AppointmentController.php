<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\SaveAppointmentRequest as SaveRequest;
use App\Http\Requests\Appointments\FindAppointmentRequest as FindRequest;
use App\Http\Requests\Appointments\DeleteAppointmentRequest as DeleteRequest;
use App\Http\Requests\Appointments\PopulateCustomerAppointmentsRequest as PopulateRequest;

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

    public function customerAppointments(PopulateRequest $request)
    {
        $options = $request->options();

        $appointments = $this->appointment->all($options);
        $appointments = $this->appointment->paginate();
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    public function trashedAppointments(PopulateRequest $request)
    {
        $options = $request->options();

        $appointments = $this->appointment->trasheds($options);
        $appointments = $this->appointment->paginate();
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
        $appointment = $this->appointment->save($input);

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }

    public function cancel(FindRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->cancel();

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }

    public function reschedule(RescheduleRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $input = $request->rescheduleData();
        $appointment = $this->appointment->reschedule($input);

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

    public function delete(DeleteRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->appointment->setModel($appointment);

        $force = strtobool($request->input('force'));
        $this->appointment->delete($force);

        return apiResponse($this->appointment);
    }

    public function restore(RestoreRequest $request)
    {
        $appointment = $request->getTrashedAppointment();

        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->restore();

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }
}
