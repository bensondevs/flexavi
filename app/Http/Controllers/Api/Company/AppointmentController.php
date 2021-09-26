<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\{
    SaveAppointmentRequest as SaveRequest,
    FindAppointmentRequest as FindRequest,
    ExecuteAppointmentRequest as ExecuteRequest,
    ProcessAppointmentRequest as ProcessRequest,
    CalculateAppointmentRequest as CalculateRequest,
    CancelAppointmentRequest as CancelRequest,
    RescheduleAppointmentRequest as RescheduleRequest,
    DeleteAppointmentRequest as DeleteRequest,
    RestoreAppointmentRequest as RestoreRequest,
    GenerateAppointmentInvoiceRequest as GenerateInvoiceRequest,
    PopulateCompanyAppointmentsRequest as CompanyPopulateRequest,
    PopulateCustomerAppointmentsRequest as CustomerPopulateRequest
};

use App\Http\Resources\{
    InvoiceResource,
    AppointmentResource
};

use App\Repositories\{
    AppointmentRepository,
    WorkRepository,
    InvoiceRepository,
    CalculationRepository
};

class AppointmentController extends Controller
{
    private $appointment;
    private $work;
    private $invoice;
    private $calculation;

    public function __construct(
        AppointmentRepository $appointment, 
        WorkRepository $work,
        InvoiceRepository $invoice,
        CalculationRepository $calculation
    ) {
    	$this->appointment = $appointment;
        $this->work = $work;
        $this->invoice = $invoice;
        $this->calculation = $calculation;
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

    public function trashedAppointments(CompanyPopulateRequest $request)
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

    public function view(FindRequest $request)
    {
        $appointment = $request->getAppointment();
        
        $relations = $request->relations();
        $appointment->load($relations);

        $appointment = new AppointmentResource($appointment);
        return response()->json(['appointment' => $appointment]);
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
        $appointment = $request->getPreviousAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $input = $request->rescheduleData();
        $appointment = $this->appointment->reschedule($input);

        return apiResponse($this->appointment);
    }

    public function calculate(CalculateRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->syncRevenues();

        if ($this->appointment->status != 'error') {
            return apiResponse($this->appointment);
        }

        return apiResponse($this->calculation, [
            'calculation' => $this->calculation->calculateAppointment($appointment)
        ]);
    }

    public function generateInvoice(GenerateInvoiceRequest $request)
    {
        $appointment = $request->getAppointment();
        $invoiceData = $request->validated();

        $invoice = $this->invoice->generateFromAppointment($appointment, $invoiceData);
        $invoice->load(['items', 'invoiceable']);
        $invoice = new InvoiceResource($invoice);

        return apiResponse($this->invoice, ['invoice' => $invoice]);
    }

    public function update(SaveRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $input = $request->ruleWithCompany();
        $appointment = $this->appointment->save($input);
        $appointment = new AppointmentResource($appointment);

        return apiResponse($this->appointment);
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
        $appointment = new AppointmentResource($appointment);

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }
}
