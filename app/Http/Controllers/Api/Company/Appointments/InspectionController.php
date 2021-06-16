<?php

namespace App\Http\Controllers\Api\Company\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\FindAppointmentRequest;
use App\Http\Requests\Appointments\SaveAppointmentInspectionRequest as SaveRequest;

use App\Http\Resources\InspectionResource;

use App\Repositories\InspectionRepository;
use App\Repositories\AppointmentRepository;

class InspectionController extends Controller
{
    private $inspection;
    private $appointment;

    public function __construct(
        InspectionRepository $inspection, 
        AppointmentRepository $appointment
    )
    {
        $this->inspection = $inspection;
        $this->appointment = $appointment;
    }

    public function inspection(FindAppointmentRequest $request)
    {
        $appointment = $request->getAppointment();

        $inspection = $appointment->inspection;
        $inspection = new InspectionResource($inspection);

        return response()->json(['inspection' => $inspection]);
    }

    public function assignInspection(SaveRequest $request)
    {
        $input = $request->inspectionData();
        $inspection = $this->inspection->save($input);

        return apiResponse($this->inspection, ['inspection' => $inspection]);
    }
}
