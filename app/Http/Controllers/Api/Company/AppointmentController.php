<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\SaveAppointmentRequest as SaveRequest;
use App\Http\Requests\Appointments\FindAppointmentRequest as FindRequest;
use App\Http\Requests\Appointments\ExecuteAppointmentRequest as ExecuteRequest;
use App\Http\Requests\Appointments\CancelAppointmentRequest as CancelRequest;
use App\Http\Requests\Appointments\RescheduleAppointmentRequest as RescheduleRequest;
use App\Http\Requests\Appointments\DeleteAppointmentRequest as DeleteRequest;
use App\Http\Requests\Appointments\PopulateCompanyAppointmentsRequest as CompanyPopulateRequest;
use App\Http\Requests\Appointments\PopulateCustomerAppointmentsRequest as CustomerPopulateRequest;

use App\Http\Resources\AppointmentResource;

use App\Repositories\AppointmentRepository;
use App\Repositories\WorkRepository;

class AppointmentController extends Controller
{
    private $appointment;
    private $work;

    public function __construct(AppointmentRepository $appointment, WorkRepository $work)
    {
    	$this->appointment = $appointment;
        $this->work = $work;
    }

    public function appointmentTypes()
    {
        $appointment = $this->appointment->getModel();
        $selectOptions = $appointment->typeOptions();
        return response()->json($selectOptions);
    }

    public function appointmentStatuses()
    {
        $appointment = $this->appointment->getModel();
        $selectOptions = $appointment->statusOptions();
        return response()->json($selectOptions);
    }

    public function appointmentCancellationVaults()
    {
        $appointment = $this->appointment->getModel();
        $selectOptions = $appointment->cancellationVaultOptions();
        return response()->json($selectOptions);
    }

    public function companyAppointments(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $appointments = $this->appointment->all($options);
        $appointments = $this->appointment->paginate();
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    public function customerAppointments(CustomerPopulateRequest $request)
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

        return apiResponse($this->appointment);
    }

    public function execute(ExecuteRequest $request)
    {
        $appointment = $request->getAppointment();

        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->execute();

        return apiResponse($this->appointment);
    }

    public function process(ProcessRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->process();

        return apiResponse($this->appointment);
    }

    public function cancel(CancelRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $cancelData = $request->cancelData();
        $appointment = $this->appointment->cancel($cancelData);

        return apiResponse($this->appointment);
    }

    public function reschedule(RescheduleRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $input = $request->rescheduleData();
        $appointment = $this->appointment->reschedule($input);

        return apiResponse($this->appointment);
    }

    public function generateInvoice(GenerateInvoiceRequest $request)
    {
        $appointment = $request->getAppointment();
        $invoice = $this->invoice->generateFromAppointment($appointment);

        return apiResponse($this->invoice, ['invoice' => $invoice]);
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
