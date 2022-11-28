<?php

namespace App\Http\Controllers\Api\Company\Calculation;

use App\Http\Controllers\Api\Company\CalculateAppointmentRequest;
use App\Http\Controllers\Api\Company\CalculateWorkdayRequest;
use App\Http\Controllers\Api\Company\CalculateWorklistRequest;
use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Repositories\Calculation\CalculationRepository;

class CalculationController extends Controller
{
    /**
     * Calculation Repository Class Container
     *
     * @var CalculationRepository
     */
    private $calculation;

    /**
     * Controller constructor method
     *
     * @param CalculationRepository $calculation
     * @return void
     */
    public function __construct(CalculationRepository $calculation)
    {
        $this->calculation = $calculation;
    }

    /**
     * Get calculation of an appointment
     *
     * @param CalculateAppointmentRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentCalculation(CalculateAppointmentRequest $request)
    {
        $appointment = $request->getAppointment();
        $calculation = $this->calculation->calculateAppointment($appointment);
        return apiResponse($this->calculation);
    }

    /**
     * Get calculation of a worklist
     *
     * @param CalculateWorklistRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function worklistCalculation(CalculateWorklistRequest $request)
    {
        $worklist = $request->getWorklist();
        $calculation = $this->calculation->calculateWorklist($worklist);
        return apiResponse($this->calculation);
    }

    /**
     * Get calculation of a workday
     *
     * @param CalculateWorkdayRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function workdayCalculation(CalculateWorkdayRequest $request)
    {
        $workday = $request->getWorkday();
        $calculation = $this->calculation->calculateWorkday($workday);
        return apiResponse($this->calculation);
    }
}
