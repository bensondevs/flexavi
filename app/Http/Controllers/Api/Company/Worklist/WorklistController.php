<?php

namespace App\Http\Controllers\Api\Company\Worklist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Worklists\{FindWorklistRequest as FindRequest};
use App\Http\Requests\Company\Worklists\AssignCarRequest;
use App\Http\Requests\Company\Worklists\CalculateWorklistRequest as CalculateRequest;
use App\Http\Requests\Company\Worklists\DeleteWorklistRequest as DeleteRequest;
use App\Http\Requests\Company\Worklists\MoveWorklistAppointmentRequest as MoveRequest;
use App\Http\Requests\Company\Worklists\PopulateCompanyWorklistsRequest as CompanyPopulateRequest;
use App\Http\Requests\Company\Worklists\PopulateEmployeeWorklistsRequest as EmployeePopulateRequest;
use App\Http\Requests\Company\Worklists\PopulateWorkdayWorklistsRequest as WorkdayPopulateRequest;
use App\Http\Requests\Company\Worklists\ProcessWorklistRequest as ProcessRequest;
use App\Http\Requests\Company\Worklists\RestoreWorklistRequest as RestoreRequest;
use App\Http\Requests\Company\Worklists\SaveWorklistRequest as SaveRequest;
use App\Http\Requests\Company\Worklists\SortingRouteRequest as RouteRequest;
use App\Http\Requests\Company\Worklists\TrashedWorklistViewRequest as TrashedViewRequest;
use App\Http\Resources\Worklist\WorklistResource;
use App\Repositories\{Worklist\WorklistCarRepository, Worklist\WorklistEmployeeRepository, Worklist\WorklistRepository};
use Illuminate\Http\Response;

class WorklistController extends Controller
{
    /**
     * Worklist Repository Class Container
     *
     * @var WorklistRepository
     */
    private WorklistRepository $worklist;

    /**
     * Worklist Employee Repository Class Container
     *
     * @var WorklistEmployeeRepository
     */
    private WorklistEmployeeRepository $worklistEmployee;

    /**
     * Worklist Employee Repository Class Container
     *
     * @var WorklistCarRepository
     */
    private WorklistCarRepository $worklistCar;

    /**
     * Controller constructor method
     *
     * @param WorklistRepository $worklist
     * @param WorklistEmployeeRepository $worklistEmployee
     * @param WorklistCarRepository $worklistCar
     * @return void
     */
    public function __construct(
        WorklistRepository         $worklist,
        WorklistEmployeeRepository $worklistEmployee,
        WorklistCarRepository      $worklistCar
    )
    {
        $this->worklist = $worklist;
        $this->worklistEmployee = $worklistEmployee;
        $this->worklistCar = $worklistCar;
    }

    /**
     * Populate with company worklists
     *
     * @param CompanyPopulateRequest $request
     * @return Response
     */
    public function companyWorklists(CompanyPopulateRequest $request)
    {
        $options = $request->options();
        $worklists = $this->worklist->all($options);
        $worklists = $this->worklist->paginate($options['per_page']);

        return response()->json([
            'worklists' => WorklistResource::apiCollection($worklists),
        ]);
    }

    /**
     * Populate with company worklists
     *
     * @param EmployeePopulateRequest $request
     * @return Response
     */
    public function employeeWorklists(EmployeePopulateRequest $request)
    {
        $options = $request->options();
        $worklists = $this->worklist->all($options, true);

        return response()->json([
            'worklists' => WorklistResource::apiCollection($worklists),
        ]);
    }

    /**
     * Populate workday's worklists
     *
     * @param WorkdayPopulateRequest $request
     * @return Response
     */
    public function workdayWorklists(WorkdayPopulateRequest $request)
    {
        $options = $request->options();
        $worklists = $this->worklist->all($options);
        $worklists = $this->worklist->paginate($options['per_page']);

        return response()->json([
            'worklists' => WorklistResource::apiCollection($worklists),
        ]);
    }

    /**
     * Populate soft-deleted worklists
     *
     * @param CompanyPopulateRequest $request
     * @return Response
     */
    public function trashedWorklists(CompanyPopulateRequest $request)
    {
        $options = $request->options();
        $worklists = $this->worklist->trasheds($options, true);
        $worklists = $this->worklist->paginate($options['per_page']);

        return response()->json([
            'worklists' => WorklistResource::apiCollection($worklists),
        ]);
    }

    /**
     * View worklist detail
     *
     * @param FindRequest $request
     * @return Response
     */
    public function view(FindRequest $request)
    {
        $worklist = $request->getWorklist()->load($request->relations());

        return response()->json([
            'worklist' => new WorklistResource($worklist),
        ]);
    }

    /**
     * View trashed worklist detail
     *
     * @param TrashedViewRequest $request
     * @return Response
     */
    public function trashedView(TrashedViewRequest $request)
    {
        $worklist = $request->getTrashedWorklist()->load($request->relations());

        return response()->json([
            'worklist' => new WorklistResource($worklist),
        ]);
    }

    /**
     * Store worklist
     *
     * @param SaveRequest $request
     * @return Response
     */
    public function store(SaveRequest $request)
    {
        $this->worklist->save($request->worklistData());
        $this->worklist->assignCar($request->getCar());
        $this->worklist->assignEmployees($request->employee_ids);

        return apiResponse($this->worklist, [
            'worklist' => new WorklistResource(
                $this->worklist->getModel()->fresh()
            ),
        ]);
    }

    /**
     * Assign car to worklist
     *
     * @param AssignCarRequest $request
     * @return Response
     */
    public function assignCar(AssignCarRequest $request)
    {
        $this->worklist->setModel($request->getWorklist());
        $worklist = $this->worklist->assignCar($request->getCar());

        return apiResponse($this->worklist, [
            'worklist' => new WorklistResource($worklist),
        ]);
    }

    /**
     * Display route of the worklist
     *
     * @param RouteRequest $request
     * @return Response
     */
    public function route(RouteRequest $request)
    {
        $input = $request->validated();
        $this->worklist->setModel($request->getWorklist());
        $worklist = $this->worklist->saveSortingRoute($input);

        return apiResponse($this->worklist, [
            'worklist' => new WorklistResource($worklist->fresh()),
        ]);
    }

    /**
     * Process worklist and set the status to
     * WorklistStatus::Processed
     *
     * @param ProcessRequest $request
     * @return Response
     */
    public function process(ProcessRequest $request)
    {
        $this->worklist->setModel($request->getWorklist());
        $worklist = $this->worklist->process();

        return apiResponse($this->worklist, [
            'worklist' => new WorklistResource($worklist->fresh()),
        ]);
    }

    /**
     * Show financial calculation of worklist
     *
     * @param CalculateRequest $request
     * @return Response
     */
    public function calculate(CalculateRequest $request)
    {
        $this->worklist->setModel($request->getWorklist());
        $worklist = $this->worklist->calculate();

        return apiResponse($this->worklist, [
            'worklist' => new WorklistResource($worklist->fresh()),
        ]);
    }

    /**
     * Update Worklist Data
     *
     * @param SaveRequest $request
     * @return Response
     */
    public function update(SaveRequest $request)
    {
        $this->worklist->setModel($request->getWorklist());
        $this->worklist->save($request->worklistData());
        $this->worklist->unassignCar();
        $this->worklist->assignCar($request->getCar());
        $this->worklist->unassignEmployees();
        $worklist = $this->worklist->assignEmployees($request->employee_ids);

        return apiResponse($this->worklist, [
            'worklist' => new WorklistResource($worklist->fresh()),
        ]);
    }

    /**
     * Delete worklist
     *
     * @param DeleteRequest $request
     * @return Response
     */
    public function delete(DeleteRequest $request)
    {
        $this->worklist->setModel($request->getWorklist());
        $this->worklist->delete(strtobool($request->input('force')));

        return apiResponse($this->worklist);
    }

    /**
     * Restore worklist
     *
     * @param RestoreRequest $request
     * @return Response
     */
    public function restore(RestoreRequest $request)
    {
        $this->worklist->setModel($request->getTrashedWorklist());
        $worklist = $this->worklist->restore();

        return apiResponse($this->worklist, [
            'worklist' => new WorklistResource($worklist),
        ]);
    }

    /**
     * Move Appointment to other worklist or unplanned appointments
     *
     * @param MoveRequest $request
     * @return Response
     */
    public function move(MoveRequest $request)
    {
        $appointment = $request->getAppointment();
        $toWorklist = $request->getToWorklist();
        $fromWorklist = $request->getFromWorklist();

        $this->worklist->moveAppointment($fromWorklist, $toWorklist, $appointment);

        return apiResponse($this->worklist);
    }
}
