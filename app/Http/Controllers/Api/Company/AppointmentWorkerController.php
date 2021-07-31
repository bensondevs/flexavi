<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\AppointmentWorkers\PopulateAppointmentWorkersRequest as PopulateRequest;
use App\Http\Requests\AppointmentWorkers\SaveAppointmentWorkerRequest as SaveRequest;
use App\Http\Requests\AppointmentWorkers\FindAppointmentWorkerRequest as FindRequest;

use App\Http\Resources\AppointmentWorkerResource;

use App\Repositories\AppointmentWorkerRepository as WorkerRepository;

use App\Models\AppointmentWorker;

class AppointmentWorkerController extends Controller
{
    private $worker;

    public function __construct(WorkerRepository $worker)
    {
    	$this->worker = $worker;
    }

    public function companyAppointmentWorkers(PopulateRequest $request)
    {
    	$options = $request->options();
    	
    	$workers = $this->worker->all($options);
    	$workers = $this->worker->paginate();
    	$workers = AppointmentWorkerResource::apiCollection($workers);

    	return response()->json(['workers' => $workers]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->validated();
    	$worker = $this->worker->save($input);

    	return apiResponse($this->worker, ['worker' => $worker]);
    }

    public function update(SaveRequest $request)
    {
        $worker = $request->getAppointmentWorker();
    	$worker = $this->worker->setModel($worker);

    	$input = $request->validated();
    	$worker = $this->worker->save($input);

    	return apiResponse($this->worker, ['worker' => $worker]);
    }

    public function delete(FindRequest $request)
    {
        $worker = $request->getAppointmentWorker();

    	$this->worker->setModel($worker);
    	$this->worker->delete();

    	return apiResponse($this->worker);
    }
}
