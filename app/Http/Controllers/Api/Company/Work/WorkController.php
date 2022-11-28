<?php

namespace App\Http\Controllers\Api\Company\Work;

use App\Enums\Work\WorkStatus;
use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Api\Company\MakrUnfinishRequest;
use App\Http\Controllers\Api\Company\ProcessRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ExecuteWorks\{ExecuteWorkRequest as ExecuteRequest};
use App\Http\Requests\Company\ExecuteWorks\MarkUnfinishedWorkRequest as MarkUnfinishRequest;
use App\Http\Requests\Company\Works\{Appointments\PopulateAppointmentWorksRequest as AppointmentPopulateRequest,};
use App\Http\Requests\Company\Works\DeleteWorkRequest as DeleteRequest;
use App\Http\Requests\Company\Works\FindWorkRequest as FindRequest;
use App\Http\Requests\Company\Works\MarkWorkFinishRequest as MarkFinishRequest;
use App\Http\Requests\Company\Works\PopulateAppointmentFinsihedWorksRequest as AppointmentFinishedPopulateRequest;
use App\Http\Requests\Company\Works\PopulateCompanyWorksRequest as CompanyPopulateRequest;
use App\Http\Requests\Company\Works\RestoreWorkRequest as RestoreRequest;
use App\Http\Requests\Company\Works\SaveWorkRequest as SaveRequest;
use App\Http\Resources\Work\WorkResource;
use App\Models\Appointment\Appointment;
use App\Repositories\{Revenue\RevenueRepository, Work\WorkRepository};


class WorkController extends Controller
{
    /**
     * Work repository class container
     *
     * @var WorkRepository
     */
    private $work;

    /**
     * Revenue repository class container
     *
     * @var RevenueRepository
     */
    private $revenue;

    /**
     * Controller constructor method
     *
     * @param WorkRepository $work
     * @param RevenueRepository $revenue
     * @return void
     */
    public function __construct(WorkRepository $work, RevenueRepository $revenue)
    {
        $this->work = $work;
        $this->revenue = $revenue;
    }

    /**
     * Populate company works
     *
     * @param CompanyPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyWorks(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Populate with appointment works
     *
     * @param AppointmentPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentWorks(AppointmentPopulateRequest $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);
        $options = $request->options();

        $works = $this->work->appointmentWorks($appointment, $options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Populate with appointment finished works
     *
     * @param AppointmentFinishedPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentFinishedWorks(AppointmentFinishedPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Populate with appointment trashed works
     *
     * @param CompanyPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedWorks(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->trasheds($options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Store work and attach it to specified model
     * The specified model can be appointment or quotation
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $work = $this->work->save($input);

        if ($request->has('appointment_id')) {
            $appointment = $request->getAppointment();
            $work = $this->work->attachTo($appointment);
        }

        if ($request->has('quotation_id')) {
            $quotation = $request->getQuotation();
            $work = $this->work->attachTo($quotation);
        }

        $work = new WorkResource($work->refresh());
        return apiResponse($this->work, ['work' => $work]);
    }

    /**
     * View work details by just specifying ID in request
     *
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $work = $request->getWork();
        $work->load(['appointments', 'quotations']);

        if ($work->status == WorkStatus::Finished) {
            $work->load(['revenueable.revenue']);
        }

        $work = new WorkResource($work);
        return response()->json(['work' => $work]);
    }

    /**
     * Execute created work and set the work status as
     * \App\Enums\Work\WorkStatus::InProcess
     *
     * @param ExecuteRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function execute(ExecuteRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $appointment = $request->getAppointment();
        $executionData = $request->executionData();
        $this->work->execute($appointment, $executionData);

        return apiResponse($this->work);
    }

    /**
     * Process in process work and set the work status as
     * \App\Enums\Work\WorkStatus::Processed
     *
     * @param ProcessRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function process(ProcessRequest $request)
    {
        $work = $request->getWork();

        $this->work->setModel($work);
        $this->work->process();

        return apiResponse($this->work);
    }

    /**
     * Mark processed work as finished and change the status to
     * \App\Enums\Work\WorkStatus::Finished
     *
     * @param MarkFinishRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function markFinish(MarkFinishRequest $request)
    {
        $appointment = $request->getAppointment();

        $work = $request->getWork();
        $work = $this->work->setModel($work);

        $finishNote = $request->finish_note;
        $work = $this->work->markFinish($appointment, $finishNote);

        return apiResponse($this->work);
    }

    /**
     * Mark work as unfinished to be continued at next appointment
     * The unfinihsed work will have status of \App\Enums\Work\WorkStatus::Unfinished
     *
     * @param MakrUnfinishRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function markUnfinish(MarkUnfinishRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $unfinishData = $request->validated();
        $this->work->markUnfinish($unfinishData);

        return apiResponse($this->work);
    }

    /**
     * Update the work data
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $input = $request->onlyInRules();
        $this->work->save($input);

        return apiResponse($this->work);
    }

    /**
     * Delete work
     *
     * @param DeleteRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);
        $this->work->delete();

        return apiResponse($this->work);
    }

    /**
     * Restore work
     *
     * @param RestoreRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $work = $request->getWork();

        $this->work->setModel($work);
        $this->work->restore();

        return apiResponse($this->work);
    }
}
