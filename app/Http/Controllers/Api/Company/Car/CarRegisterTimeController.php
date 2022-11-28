<?php

namespace App\Http\Controllers\Api\Company\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CarRegisterTimes\{MarkReturnCarRegisterTimeRequest as MarkReturnRequest};
use App\Http\Requests\Company\CarRegisterTimes\DeleteCarRegisterTimeRequest as DeleteRequest;
use App\Http\Requests\Company\CarRegisterTimes\MarkOutCarRegisterTimeRequest as MarkOutRequest;
use App\Http\Requests\Company\CarRegisterTimes\PopulateCarRegisterTimesRequest as PopulateRequest;
use App\Http\Requests\Company\CarRegisterTimes\RegisterCarTimeRequest as RegisterRequest;
use App\Http\Requests\Company\CarRegisterTimes\RegisterWorklistCarTimeRequest as RegisterWorklistRequest;
use App\Http\Requests\Company\CarRegisterTimes\UpdateCarRegisterTimeRequest as UpdateRequest;
use App\Http\Resources\{Car\CarRegisterTimeResource, Car\CarResource};
use App\Repositories\Car\CarRegisterTimeRepository;

class CarRegisterTimeController extends Controller
{
    /**
     * Repository container variable
     *
     * @var CarRegisterTimeRepository
     */
    private CarRegisterTimeRepository $time;

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
     * @param PopulateRequest $request
     * @return
     */
    public function carRegisterTimes(PopulateRequest $request)
    {
        $options = $request->options();
        $times = $this->time->all($options);
        $times = $this->time->paginate($options['per_page']);

        return response()->json([
            'car' => new CarResource($request->getCar()),
            'car_register_times' => CarRegisterTimeResource::apiCollection(
                $times
            ),
        ]);
    }

    /**
     * Register time to a car.
     *
     * @param RegisterRequest $request
     * @return
     */
    public function registerTime(RegisterRequest $request)
    {
        $this->time->setCar($request->getCar());
        $time = $this->time->register($request->validated());

        return apiResponse($this->time, [
            'car_register_time' => new CarRegisterTimeResource($time),
        ]);
    }

    /**
     * Register time to a car based on worklist.
     *
     * @param RegisterRequest $request
     * @return
     */
    public function registerToWorklist(RegisterWorklistRequest $request)
    {
        $this->time->setCar($request->getCar());
        $time = $this->time->registerWorklist($request->getWorklist());

        return apiResponse($this->time, [
            'car_register_time' => new CarRegisterTimeResource($time),
        ]);
    }

    /**
     * Mark car as out.
     *
     * @param MarkOutRequest $request
     * @return
     */
    public function markOut(MarkOutRequest $request)
    {
        $this->time->setModel($request->getCarRegisterTime());
        $time = $this->time->markOut($request->validated());

        return apiResponse($this->time, [
            'car_register_time' => new CarRegisterTimeResource($time),
        ]);
    }

    /**
     * Mark car as returned.
     *
     * @param MarkOutRequest $request
     * @return
     */
    public function markReturn(MarkReturnRequest $request)
    {
        $this->time->setModel($request->getCarRegisterTime());
        $time = $this->time->markReturn($request->validated());

        return apiResponse($this->time, [
            'car_register_time' => new CarRegisterTimeResource($time),
        ]);
    }

    /**
     * Update car register time.
     *
     * @param UpdateRequest $request
     * @return
     */
    public function update(UpdateRequest $request)
    {
        $this->time->setModel($request->getCarRegisterTime());
        $time = $this->time->update($request->validated());

        return apiResponse($this->time, [
            'car_register_time' => new CarRegisterTimeResource($time),
        ]);
    }

    /**
     * Delete registered time of a car.
     *
     * @param DeleteRequest $request
     * @return
     */
    public function unregister(DeleteRequest $request)
    {
        $this->time->setModel($request->getCarRegisterTime());
        $this->time->unregister(strtobool($request->input('force')));

        return apiResponse($this->time);
    }
}
