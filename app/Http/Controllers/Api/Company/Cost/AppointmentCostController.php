<?php

namespace App\Http\Controllers\Api\Company\Cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Costs\Appointments\PopulateAppointmentCostsRequest as PopulateRequest;
use App\Http\Requests\Company\Costs\Appointments\SaveAppointmentCostRequest as SaveRequest;
use App\Http\Requests\Company\Costs\RecordCostRequest as RecordRequest;
use App\Http\Requests\Company\Costs\RecordManyCostsRequest as RecordManyRequest;
use App\Http\Requests\Company\Costs\TruncateCostsRequest as TruncateRequest;
use App\Http\Requests\Company\Costs\UnrecordCostRequest as UnrecordRequest;
use App\Http\Requests\Company\Costs\UnrecordManyCostsRequest as UnrecordManyRequest;
use App\Http\Resources\Cost\CostResource;
use App\Repositories\Cost\CostRepository;

class AppointmentCostController extends Controller
{
    /**
     * Repository Container
     *
     * @var CostRepository
     */
    private $cost;

    /**
     * Create New Controller Instance
     *
     * @return void
     */
    public function __construct(CostRepository $cost)
    {
        $this->cost = $cost;
    }

    /**
     * Populate costs recorded under an appointment
     *
     * @param PopulateRequest $request
     * @return json
     */
    public function appointmentCosts(PopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options, true);
        $costs = CostResource::apiCollection($costs);

        return response()->json(['costs' => $costs]);
    }

    /**
     * Store cost and attach it to appointment with settings to record to parents level like worklist and workday as well
     *
     * @param SaveRequest $request
     * @return json
     */
    public function storeRecord(SaveRequest $request)
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

    /**
     * Record cost to appointment
     *
     * @param RecordRequest $request
     * @return json
     */
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

    /**
     * Record many costs to appointment
     *
     * @param RecordManyRequest $request
     * @return json
     */
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

    /**
     * Unrecord many costs from appointment
     *
     * @param UnrecordManyRequest $request
     * @return json
     */
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

    /**
     * Unrecord cost from appointment
     *
     * @param UnrecordRequest $request
     * @return json
     */
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

    /**
     * Truncate costs from appointment
     *
     * @param UnrecordManyRequest $request
     * @return json
     */
    public function truncate(TruncateRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->cost->truncate($appointment);

        return apiResponse($this->cost);
    }
}
