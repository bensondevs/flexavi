<?php

namespace App\Http\Controllers\Api\Company\Costs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Costs\RecordCostRequest as RecordRequest;
use App\Http\Requests\Costs\RecordManyCostsRequest as RecordManyRequest;
use App\Http\Requests\Costs\UnrecordCostRequest as UnrecordRequest;
use App\Http\Requests\Costs\UnrecordManyCostsRequest as UnrecordManyRequest;
use App\Http\Requests\Costs\TruncateCostsRequest as TruncateRequest;
use App\Http\Requests\Costs\Appointments\SaveAppointmentCostRequest as SaveRequest;
use App\Http\Requests\Costs\Appointments\PopulateAppointmentCostsRequest as PopulateRequest;

use App\Repositories\CostRepository;

use App\Http\Resources\CostResource;

class AppointmentCostController extends Controller
{
    private $cost;

    public function __construct(CostRepository $cost)
    {
        $this->cost = $cost;
    }

    public function appointmentCosts(PopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options, true);
        $costs = CostResource::apiCollection($costs);

        return response()->json(['costs' => $costs]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $cost = $this->cost->save($input);

        $appointment = $request->getAppointment();
        $this->cost->record($appointment);

        if ($request->record_in_worklist && $appointment->worklist) {
            $this->cost->record($appointment->worklist);
        }

        if ($request->record_in_workday && $appointment->workday) {
            $this->cost->record($appointment->workday);
        }

        return apiResponse($this->cost);
    }

    public function record(RecordRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $appointment = $request->getAppointment();
        $this->cost->record($appointment);

        if ($request->record_in_worklist && $appointment->worklist) {
            $this->cost->record($appointment->worklist);
        }

        if ($request->record_in_workday && $appointment->workday) {
            $this->cost->record($appointment->workday);
        }

        return apiResponse($this->cost);
    }

    public function recordMany(RecordManyRequest $request)
    {
        $appointment = $request->getAppointment();
        $costIds = $request->costIdsArray();
        $this->cost->recordMany($appointment, $costIds);

        if ($request->record_in_worklist && $appointment->worklist) {
            $this->cost->recordMany($appointment->worklist);
        }

        if ($request->record_in_workday && $appointment->workday) {
            $this->cost->recordMany($appointment->workday);
        }

        return apiResponse($this->cost);
    }

    public function unrecord(UnrecordRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $appointment = $request->getAppointment();
        $this->cost->unrecord($appointment);

        if ($request->unrecord_from_worklist && $appointment->worklist) {
            $this->cost->unrecord($appointment->worklist);
        }

        if ($request->unrecord_from_workday && $appointment->workday) {
            $this->cost->unrecord($appointment->workday);
        }

        return apiResponse($this->cost);
    }

    public function unrecordMany(UnrecordManyRequest $request)
    {
        $appointment = $request->getAppointment();
        $costIds = $request->costIdsArray();
        $this->cost->unrecordMany($appointment, $costIds);

        if ($request->unrecord_from_worklist && $appointment->worklist) {
            $this->cost->unrecord($appointment->worklist);
        }

        if ($request->unrecord_from_workday && $appointment->workday) {
            $this->cost->unrecord($appointment->workday);
        }

        return apiResponse($this->cost);
    }

    public function truncate(TruncateRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->cost->truncate($appointment);

        return apiResponse($this->cost);
    }
}
