<?php

namespace App\Http\Controllers\Api\Company\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\FindAppointmentRequest;
use App\Http\Requests\Appointments\SaveAppointmentWorkRequest as SaveRequest;

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;
use App\Repositories\AppointmentRepository;

class WorkController extends Controller
{
    private $work;
    private $appointment;

    public function __construct(
        WorkRepository $work, 
        AppointmentRepository $appointment
    )
    {
        $this->work = $work;
        $this->appointment = $appointment;
    }

    public function work(FindAppointmentRequest $request)
    {
        $appointment = $request->getAppointment();

        $work = $appointment->work;
        $work = new WorkResource($work);

        return response()->json(['work' => $work]);
    }

    public function assignWork(SaveRequest $request)
    {
        $input = $request->workData();
        $work = $this->work->save($input);

        return apiResponse($this->work, ['work' => $work]);
    }
}
