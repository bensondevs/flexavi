<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\CarRegisterTimes\{
    PopulateCarRegisterTimesRequest as PopulateRequest,
    RegisterCarTimeRequest as RegisterRequest,
    RegisterWorklistCarTimeRequest as RegisterWorklistRequest,
    UpdateCarRegisterTimeRequest as UpdateRequest,
    MarkOutCarRegisterTimeRequest as MarkOutRequest,
    MarkReturnCarRegisterTimeRequest as MarkReturnRequest,
    DeleteCarRegisterTimeRequest as DeleteRequest
};

use App\Http\Resources\CarRegisterTimeResource as Resource;

use App\Repositories\CarRegisterTimeRepository;

class CarRegisterTimeController extends Controller
{
    /**
     * Repository container variable
     * 
     * @var \App\Models\Car  $car 
     */
    private $time;

    /**
     * Initiate the controller creation
     * 
     * @return void
     */
    public function __construct(CarRegisterTimeRepository $time)
    {
        $this->time = $time;
    }

    /**
     * Populate registered times of a car.
     * 
     * @param PopulateRequest  $request 
     * @return  
     */
    public function carRegisterTimes(PopulateRequest $request)
    {
        $options = $request->options();

        $times = $this->time->all($options, true);
        $times = Resource::apiCollection($times);

        return response()->json([
            'car' => $request->getCar(),
            'car_register_times' => $times,
        ]);
    }

    /**
     * Register time to a car.
     * 
     * @param RegisterRequest  $request
     * @return  
     */
    public function registerTime(RegisterRequest $request)
    {
        $car = $request->getCar();
        $car = $this->time->setCar($car);

        $input = $request->validated();
        $time = $this->time->register($input);

        return apiResponse($this->time);
    }

    /**
     * Register time to a car based on worklist.
     * 
     * @param RegisterRequest  $request
     * @return  
     */
    public function registerToWorklist(RegisterWorklistRequest $request)
    {
        $car = $request->getCar();
        $this->time->setCar($car);

        $worklist = $request->getWorklist();
        $time = $this->time->registerWorklist($worklist);

        return apiResponse($this->time);
    }

    /**
     * Mark car as out.
     * 
     * @param MarkOutRequest  $request
     * @return  
     */
    public function markOut(MarkOutRequest $request)
    {
        $time = $request->getCarRegisterTime();
    
        $this->time->setModel($time);
        $this->time->markOut();

        return apiResponse($this->time);
    }

    /**
     * Mark car as returned.
     * 
     * @param MarkOutRequest  $request
     * @return  
     */
    public function markReturn(MarkReturnRequest $request)
    {
        $time = $request->getCarRegisterTime();

        $this->time->setModel($time);
        $this->time->markReturn();

        return apiResponse($this->time);
    }

    /**
     * Update car register time.
     * 
     * @param UpdateRequest  $request
     * @return  
     */
    public function update(UpdateRequest $request)
    {
        $time = $request->getCarRegisterTime();
        $this->time->setModel($time);

        $input = $request->validated();
        $this->time->update($input);

        return apiResponse($this->time);
    }

    /**
     * Delete registered time of a car.
     * 
     * @param DeleteRequest  $request
     * @return  
     */
    public function unregister(DeleteRequest $request)
    {
        $time = $request->getCarRegisterTime();
        $this->time->setModel($time);

        $force = $request->input('force');
        $this->time->unregister($force);

        return apiResponse($this->time);
    }
}
