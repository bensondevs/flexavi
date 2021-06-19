<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\SubAppointments\SaveSubAppointmentRequest as SaveRequest;
use App\Http\Requests\SubAppointments\PopulateSubAppointmentsRequest as PopulateRequest;

use App\Http\Resources\SubAppointmentResource;

use App\Repositories\SubAppointmentRepository;

class SubAppointmentController extends Controller
{
    private $subAppointment;

    public function __construct(SubAppointmentRepository $subAppointment)
    {
        $this->subAppointment = $subAppointment;
    }

    public function subAppointments(PopulateRequest $request)
    {
        $options = $request->options();

        $subAppointments = $this->subAppointment->all($options);
        // $subAppointments = $this->subAppointment->paginate();
        $subAppointments = SubAppointmentResource::collection($subAppointments);

        return response()->json(['sub_appointments' => $subAppointments]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->onlyInRules();
        $subAppointment = $this->subAppointment->save($input);

        return apiResponse($this->subAppointment, ['sub_appointment' => $subAppointment]);
    }

    public function update(SaveRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $subAppointment = $this->subAppointment->setModel($subAppointment);

        $input = $request->onlyInRules();
        $subAppointment = $this->subAppointment->save($input);

        return apiResponse($this->subAppointment, ['sub_appointment' => $subAppointment]);
    }

    public function delete(DeleteRequest $request)
    {
        $subAppointment = $request->getSubAppointment();

        $this->subAppointment->setModel($subAppointment);
        $this->subAppointment->delete();

        return apiResponse($this->subAppointment);
    }
}
