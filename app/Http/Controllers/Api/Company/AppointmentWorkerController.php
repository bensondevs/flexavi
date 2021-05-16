<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\AppointmentWorkers\PopulateAppointmentWorkersRequest;
use App\Http\Requests\AppointmentWorkers\SaveAppointmentWorkerRequest;
use App\Http\Requests\AppointmentWorkers\FindAppointmentWorkerRequest;

use App\Http\Resources\AppointmentWorkerResource;

use App\Repositories\AppointmentWorkerRepository;

use App\Models\AppointmentWorker;

class AppointmentWorkerController extends Controller
{
    private $worker;

    public function __construct(AppointmentWorkerRepository $worker)
    {
    	$this->worker = $worker;
    }

    public function companyAppointmentWorkers(
    	PopulateAppointmentWorkersRequest $request
    )
    {
    	$options = $request->options();
    	
    	$workers = $this->worker->all($options);
    	$workers = $this->worker->paginate();
    	$workers->date = AppointmentWorkerResource::collection($workers);

    	return response()->json(['workers' => $workers]);
    }

    public function store(SaveAppointmentWorkerRequest $request)
    {
    	$input = $request->onlyInRules();
    	$worker = $this->worker->save($input);

    	return apiResponse($this->worker, $worker);
    }

    public function update(SaveAppointmentWorkerRequest $request)
    {
    	$input = $request->onlyInRules();
    	$worker = $this->worker->setModel($request->getWorker());
    	$worker = $this->worker->save($input);

    	return apiResponse($this->worker, $worker);
    }

    public function delete(FindAppointmentWorkerRequest $request)
    {
    	$this->worker->setModel($request->getWorker());
    	$this->worker->delete();

    	return apiResponse($this->worker);
    }
}
