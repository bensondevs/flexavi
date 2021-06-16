<?php

namespace App\Http\Controllers\Api\Company\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\FindAppointmentRequest;

use App\Http\Resources\InspectionResource;

use App\Repositories\AppointmentRepository;
use App\Repositories\PaymentReminderRepository;

class PaymentReminderController extends Controller
{
    private $reminder;
    private $appointment;

    public function __construct(
        AppointmentRepository $appointment, 
        PaymentReminderRepository $reminder
    )
    {
        $this->reminder = $reminder;
        $this->appointment = $appointment;
    }

    public function reminder()
    {
        $appointment = $request->getAppointment();

        $reminder = $appointment->paymentReminder;
        $reminder = new PaymentReminderResource($reminder);

        return response()->json(['reminder' => $reminder]);
    }

    public function assignReminder(SaveRequest $request)
    {
        $input = $request->reminderData();
        $reminder = $this->reminder->save($input);

        return apiResponse($this->reminder, ['reminder' => $reminder]);
    }
}
