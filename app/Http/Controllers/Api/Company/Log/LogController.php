<?php

namespace App\Http\Controllers\Api\Company\Log;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Logs\FindLogRequest as FindRequest;
use App\Http\Requests\Company\Logs\PopulateLogsRequest as PopulateRequest;
use App\Repositories\Log\LogRepository;
use Illuminate\Http\Response;

class LogController extends Controller
{
    /**
     * Log Repository Class Container
     *
     * @var LogRepository
     */
    private $log;

    /**
     * Controller constructor method
     *
     * @param LogRepository $log
     */
    public function __construct(LogRepository $log)
    {
        $this->log = $log;
    }

    /**
     * Display a listing of logs that belongs to the company
     *
     * @param PopulateRequest $request
     * @return Response
     */
    public function companyLogs(PopulateRequest $request)
    {
        $options = $request->companyOptions();
        $logs = $this->log->all($options);
        $logs = $this->log->datePaginate();
        $logs = $this->log->groupByDateAndHour();

        return response()->json(['logs' => $logs]);
    }

    /**
     * Populate company trashed logs
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedLogs(PopulateRequest $request)
    {
        $options = $request->companyOptions();
        $logs = $this->log->trasheds($options);
        $logs = $this->log->datePaginate();
        $logs = $this->log->groupByDateAndHour();

        return response()->json(['logs' => $logs]);
    }

    /**
     * Display a listing of logs that belongs to the company
     *
     * @param FindRequest $request
     * @return Response
     */
    public function restore(FindRequest $request)
    {
        $logs = $request->getLogs();
        $this->log->setCollection($logs);
        $this->log->restoreMany();

        return apiResponse($this->log);
    }

    /**
     * Display a listing of logs that belongs to the company
     *
     * @param FindRequest $request
     * @return Response
     */
    public function delete(FindRequest $request)
    {
        $logs = $request->getLogs();
        $this->log->setCollection($logs);
        $this->log->deleteMany($request->force);

        return apiResponse($this->log);
    }
}
