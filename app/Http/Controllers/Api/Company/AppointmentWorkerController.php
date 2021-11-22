<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\AppointmentWorkers\{
    PopulateAppointmentWorkersRequest as PopulateRequest,
    SaveAppointmentWorkerRequest as SaveRequest,
    FindAppointmentWorkerRequest as FindRequest
};

use App\Http\Resources\AppointmentWorkerResource;

use App\Repositories\AppointmentWorkerRepository as WorkerRepository;

use App\Models\AppointmentWorker;

class AppointmentWorkerController extends Controller
{
    /**
     * Appointment Worker Repository Class Container
     * 
     * @var \App\Repositories\AppointmentWorkerRepository
     */
    private $worker;

    /**
     * Controller constructor method
     * 
     * @param \App\Repotories\AppointmentWorkerRepository  $worker
     * @return void
     */
    public function __construct(WorkerRepository $worker)
    {
    	$this->worker = $worker;
    }

    /**
     * Populate company appointment workers
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyAppointmentWorkers(PopulateRequest $request)
    {
    	$options = $request->options();
    	
    	$workers = $this->worker->all($options);
    	$workers = $this->worker->paginate();
    	$workers = AppointmentWorkerResource::apiCollection($workers);

    	return response()->json(['workers' => $workers]);
    }

    /**
     * Store appointment worker
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
    	$input = $request->validated();
    	$worker = $this->worker->save($input);
    	return apiResponse($this->worker, ['worker' => $worker]);
    }

    /**
     * Update appointment worker
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $worker = $request->getAppointmentWorker();
    	$worker = $this->worker->setModel($worker);

    	$input = $request->validated();
    	$worker = $this->worker->save($input);

    	return apiResponse($this->worker, ['worker' => $worker]);
    }

    /**
     * Delete appointment worker
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(FindRequest $request)
    {
        $worker = $request->getAppointmentWorker();

    	$this->worker->setModel($worker);
    	$this->worker->delete();

    	return apiResponse($this->worker);
    }
}
