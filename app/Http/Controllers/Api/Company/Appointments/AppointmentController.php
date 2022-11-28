<?php

namespace App\Http\Controllers\Api\Company\Appointments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Appointments\{CalculateAppointmentRequest as CalculateRequest,
    CancelAppointmentRequest as CancelRequest,
    DeleteAppointmentRequest as DeleteRequest,
    ExecuteAppointmentRequest as ExecuteRequest,
    FindAppointmentRequest as FindRequest,
    GenerateAppointmentInvoiceRequest as GenerateInvoiceRequest,
    PopulateCompanyAppointmentsRequest as CompanyPopulateRequest,
    PopulateCustomerAppointmentsRequest as CustomerPopulateRequest,
    ProcessAppointmentRequest as ProcessRequest,
    RescheduleAppointmentRequest as RescheduleRequest,
    RestoreAppointmentRequest as RestoreRequest,
    SaveAppointmentRequest as SaveRequest};
use App\Http\Resources\{Appointment\AppointmentResource, Invoice\InvoiceResource};
use App\Repositories\{Appointment\AppointmentRepository,
    Appointment\RelatedAppointmentRepository,
    Calculation\CalculationRepository,
    Invoice\InvoiceRepository};
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    /**
     * Appointment Repository class container
     *
     * @var AppointmentRepository
     */
    private AppointmentRepository $appointment;

    /**
     * Related appointment Repository class container
     *
     * @var RelatedAppointmentRepository;
     */
    private RelatedAppointmentRepository $relatedApppointment;

    /**
     * Invoice Repository class container
     *
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoice;

    /**
     * Calculation Repository class container
     *
     * @var CalculationRepository
     */
    private CalculationRepository $calculation;

    /**
     * Controller constructor method
     *
     * @param AppointmentRepository $appointment
     * @param InvoiceRepository $invoice
     * @param CalculationRepository $calculation
     * @param RelatedAppointmentRepository $relatedAppointment
     * @return void
     */
    public function __construct(
        AppointmentRepository        $appointment,
        InvoiceRepository            $invoice,
        CalculationRepository        $calculation,
        RelatedAppointmentRepository $relatedAppointment
    )
    {
        $this->appointment = $appointment;
        $this->invoice = $invoice;
        $this->calculation = $calculation;
        $this->relatedApppointment = $relatedAppointment;
    }

    /**
     * Populate company appointments
     *
     * @param CompanyPopulateRequest $request
     * @return JsonResponse
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
     * @param CompanyPopulateRequest $request
     * @return JsonResponse
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
     * @param CustomerPopulateRequest $request
     * @return JsonResponse
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
     * @param CompanyPopulateRequest $request
     * @return JsonResponse
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
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function store(SaveRequest $request)
    {
        $input = $request->appointmentData();
        $appointment = $this->appointment->save($input);

        $appointment = new AppointmentResource($appointment->fresh());

        return apiResponse($this->appointment, [
            'appointment' => $appointment
        ]);
    }

    /**
     * Store company appointment as Draft
     *
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function draft(SaveRequest $request)
    {
        $input = $request->appointmentData();
        $appointment = $this->appointment->draft($input);

        $appointment = new AppointmentResource($appointment->fresh());

        return apiResponse($this->appointment, [
            'appointment' => $appointment
        ]);
    }

    /**
     * View company appointment
     *
     * @param FindRequest $request
     * @return JsonResponse
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
     * @param ExecuteRequest $request
     * @return JsonResponse
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
     * @param ProcessRequest $request
     * @return JsonResponse
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
     * @param CancelRequest $request
     * @return JsonResponse
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
     * @param RescheduleRequest $request
     * @return JsonResponse
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
     * @param CalculateRequest $request
     * @return JsonResponse
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
     * @param GenerateInvoiceRequest $request
     * @return JsonResponse
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

        $input = $request->appointmentData();
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
     * @param RestoreRequest $request
     * @return JsonResponse
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
