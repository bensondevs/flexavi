<?php

namespace App\Http\Controllers\Api\Company\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\FindAppointmentRequest;
use App\Http\Requests\Appointments\SaveAppointmentQuotationRequest as SaveRequest;

use App\Http\Resources\QuotationResource;

use App\Repositories\QuotationRepository;
use App\Repositories\AppointmentRepository;

class QuotationController extends Controller
{
    private $quotation;
    private $appointment;

    public function __construct(
        QuotationRepository $quotation,
        AppointmentRepository $appointment
    )
    {
        $this->quotation = $quotation;
        $this->appointment = $appointment;
    }

    public function quotation(FindAppointmentRequest $request)
    {
        $appointment = $request->getAppointment();

        $quotation = $appointment->quotation;
        $quotation = new QuotationResource($quotation);

        return response()->json(['quotation' => $quotation]);
    }

    public function assignQuotation(SaveRequest $request)
    {
        $input = $request->quotationData();
        $quotation = $this->quotation->save($input);

        return apiResponse($this->quotation, ['quotation' => $quotation]);
    }
}
