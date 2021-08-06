<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\CalculationRepository;

class CalculationController extends Controller
{
    private $calculation;

    public function __construct(CalculationRepository $calculation)
    {
        $this->calculation = $calculation;
    }

    public function appointmentCalculation(CalculateAppointmentRequest $request)
    {
        $appointment = $request->getAppointment();
        $calculation = $this->calculation->calculateAppointment($appointment);

        return apiResponse($this->calculation);
    }

    public function worklistCalculation(CalculateWorklistRequest $request)
    {
        $worklist = $request->getWorklist();
        $calculation = $this->calculation->calculateWorklist($worklist);

        return apiResponse($this->calculation);
    }
}
