<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Works\{
    DeleteWorkRequest as DeleteRequest,
    PopulateAppointmentWorksRequest as AppointmentPopulateRequest,
    PopulateAppointmentFinsihedWorksRequest as AppointmentFinishedPopulateRequest,
    PopulateCompanyWorksRequest as CompanyPopulateRequest,
    SaveWorkRequest as SaveRequest,
    FindWorkRequest as FindRequest,
    RestoreWorkRequest as RestoreRequest,
    ExecuteWorkRequest as ExecuteRequest,
    MarkWorkFinishRequest as MarkFinishRequest
};

use App\Http\Resources\WorkResource;

use App\Models\Appointment;

use App\Enums\Work\WorkStatus;

use App\Repositories\{
    WorkRepository,
    RevenueRepository,
    ExecuteWorkRepository
};

class WorkController extends Controller
{
    private $work;
    private $revenue;

    public function __construct(WorkRepository $work, RevenueRepository $revenue)
    {
    	$this->work = $work;
        $this->revenue = $revenue;
    }

    public function companyWorks(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /*public function appointmentWorks(AppointmentPopulateRequest $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);
        $options = $request->options();

        $works = $this->work->appointmentWorks($appointment, $options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }*/

    public function appointmentFinishedWorks(AppointmentFinishedPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function trashedWorks(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->trasheds($options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

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

        $work = new WorkResource($work->fresh());
        return apiResponse($this->work, ['work' => $work]);
    }

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

    /*public function execute(ExecuteRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $appointment = $request->getAppointment();
        $executionData = $request->executionData();
        $this->work->execute($appointment, $executionData);

        return apiResponse($this->work);
    }*/

    public function process(ProcessRequest $request)
    {
        $work = $request->getWork();

        $this->work->setModel($work);
        $this->work->process();

        return apiResponse($this->work);
    }

    public function markFinish(MarkFinishRequest $request)
    {
        $appointment = $request->getAppointment();

        $work = $request->getWork();
        $work = $this->work->setModel($work);

        $finishNote = $request->finish_note;
        $work = $this->work->markFinish($appointment, $finishNote);

        return apiResponse($this->work);
    }

    public function markUnfinish(MarkUnfinishRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $unfinishData = $request->validated();
        $this->work->markUnfinish($unfinishData);

        return apiResponse($this->work);
    }

    public function update(SaveRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $input = $request->onlyInRules();
        $this->work->save($input);

        return apiResponse($this->work);
    }

    public function delete(DeleteRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);
        $this->work->delete();

        return apiResponse($this->work);
    }

    public function restore(RestoreRequest $request)
    {
        $work = $request->getWork();

        $this->work->setModel($work);
        $this->work->restore();

        return apiResponse($this->work);
    }
}