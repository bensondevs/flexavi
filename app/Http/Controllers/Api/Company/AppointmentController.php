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

use App\Http\Resources\{ InvoiceResource, AppointmentResource };

use App\Repositories\{
    AppointmentRepository,
    InvoiceRepository,
    CalculationRepository
};

class AppointmentController extends Controller
{
    /**
     * Appointment Repository class container
     * 
     * @var \App\Repositori\AppointmentRepository
     */
    private $appointment;

    /**
     * Invoice Repository class container
     * 
     * @var \App\Repositories\InvoiceRepository
     */
    private $invoice;

    /**
     * Calculation Repository class container
     * 
     * @var \App\Repositories\CalculationRepository
     */
    private $calculation;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\AppointmentRepository  $appointment
     * @param \App\Repositories\InvoiceRepository  $invoice
     * @param \App\Repositories\CalculationRepository  $calculation
     * @return void
     */
    public function __construct(
        AppointmentRepository $appointment, 
        InvoiceRepository $invoice,
        CalculationRepository $calculation
    ) {
    	$this->appointment = $appointment;
        $this->invoice = $invoice;
        $this->calculation = $calculation;
    }

    /**
     * Populate company appointments
     * 
     * @param CompanyPopulateRuquest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyAppointments(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $appointments = $this->appointment->all($options);
        $appointments = $this->appointment->paginate();
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Populate company unplanned appointments
     * 
     * @param CompanyPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function unplannedAppointments(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $appointments = $this->appointment->unplanneds($options);
        $appointments = $this->appointment->paginate();
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Populate company customer appointments
     * 
     * @param CustomerPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response 
     */
    public function customerAppointments(CustomerPopulateRequest $request)
    {
        $options = $request->options();

        $appointments = $this->appointment->all($options);
        $appointments = $this->appointment->paginate();
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Populate company trashed appointments
     * 
     * @param CompanyPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedAppointments(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $appointments = $this->appointment->trasheds($options);
        $appointments = $this->appointment->paginate();
        $appointments = AppointmentResource::apiCollection($appointments);

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Store company appointment
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
        $appointment = $this->appointment->save($input);
        return apiResponse($this->appointment);
    }

    /**
     * View company appointment
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $appointment = $request->getAppointment();
        
        $relations = $request->relations();
        $appointment->load($relations);

        $appointment = new AppointmentResource($appointment);
        return response()->json(['appointment' => $appointment]);
    }

    /**
     * Execute company appointment
     * 
     * @param ExecuteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function execute(ExecuteRequest $request)
    {
        $appointment = $request->getAppointment();

        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->execute();

        return apiResponse($this->appointment);
    }

    /**
     * Process company appointment
     * 
     * @param ProcessRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function process(ProcessRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->process();

        return apiResponse($this->appointment);
    }

    /**
     * Cancel company appointment
     * 
     * @param CancelRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function cancel(CancelRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $cancelData = $request->cancelData();
        $appointment = $this->appointment->cancel($cancelData);

        return apiResponse($this->appointment);
    }

    /**
     * Reschedule company appointment
     * 
     * @param RescheduleRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function reschedule(RescheduleRequest $request)
    {
        $appointment = $request->getPreviousAppointment();
        $appointment = $this->appointment->setModel($appointment);

        $input = $request->rescheduleData();
        $appointment = $this->appointment->reschedule($input);

        return apiResponse($this->appointment);
    }

    /**
     * Calculate company appointment
     * 
     * @param CalculateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function calculate(CalculateRequest $request)
    {
        $appointment = $request->getAppointment();
        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->syncRevenues();

        if ($this->appointment->status === 'error') {
            return apiResponse($this->appointment);
        }

        return apiResponse($this->calculation, [
            'calculation' => $this->calculation->calculateAppointment($appointment)
        ]);
    }

    /**
     * Generate Invoice from appointment
     * 
     * @param GenerateInvoiceRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
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

    /**
     * Restore deleted company appointment
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $appointment = $request->getTrashedAppointment();

        $appointment = $this->appointment->setModel($appointment);
        $appointment = $this->appointment->restore();
        $appointment = new AppointmentResource($appointment);

        return apiResponse($this->appointment, ['appointment' => $appointment]);
    }
}
