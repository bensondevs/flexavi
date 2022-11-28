<?php

namespace App\Http\Controllers\Api\Company\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Cars\{UpdateRequest};
use App\Http\Requests\Company\Cars\FindCarRequest as FindRequest;
use App\Http\Requests\Company\Cars\PopulateCompanyCarsRequest as PopulateRequest;
use App\Http\Requests\Company\Cars\RestoreCarRequest as RestoreRequest;
use App\Http\Requests\Company\Cars\SaveCarRequest as SaveRequest;
use App\Http\Requests\Company\Cars\SetCarImageRequest as SetImageRequest;
use App\Http\Resources\Car\CarResource;
use App\Repositories\Car\CarRepository;
use Illuminate\Http\Response;

class CarController extends Controller
{
    /**
     * Car repository container variable
     *
     * @var CarRepository
     */
    private CarRepository $car;

    /**
     * Controller constructor method
     *
     * @param CarRepository $car
     * @return void
     */
    public function __construct(CarRepository $car)
    {
        $this->car = $car;
    }

    /**
     * Populate company cars
     *
     * @param PopulateRequest $request
     * @return Response
     */
    public function companyCars(PopulateRequest $request)
    {
        $options = $request->options();
        $cars = $this->car->all($options);
        $cars = $this->car->paginate($options['per_page']);

        return response()->json(['cars' => CarResource::apiCollection($cars)]);
    }

    /**
     * Populate company free cars
     *
     * @param PopulateRequest $request
     * @return Response
     */
    public function freeCars(PopulateRequest $request)
    {
        $options = $request->options();
        $cars = $this->car->freeCars($options);
        $cars = $this->car->paginate($options['per_page']);

        return response()->json(['cars' => CarResource::apiCollection($cars)]);
    }

    /**
     * Populate company soft-deleted cars
     *
     * @param PopulateRequest $request
     * @return Response
     */
    public function trashedCars(PopulateRequest $request)
    {
        $options = $request->options();
        $cars = $this->car->trasheds($options);
        $cars = $this->car->paginate($options['per_page']);

        return response()->json(['cars' => CarResource::apiCollection($cars)]);
    }

    /**
     * View company car
     *
     * @param SaveRequest $request
     * @return Response
     */
    public function view(FindRequest $request)
    {
        $car = $request->getCar()->load($request->relations());

        return apiResponse($this->car, [
            'car' => new CarResource($car),
        ]);
    }

    /**
     * Store company car
     *
     * @param SaveRequest $request
     * @return Response
     */
    public function store(SaveRequest $request)
    {
        $car = $this->car->save($request->ruleWithCompany());

        return apiResponse($this->car, [
            'car' => new CarResource($car),
        ]);
    }

    /**
     * Validate car insurance
     *
     * @param FindRequest $request
     * @return Response
     */
    public function validateInsurance(FindRequest $request)
    {
        $this->car->setModel($request->getCar());
        $car = $this->car->validateInsurance();

        return apiResponse($this->car, [
            'car' => new CarResource($car),
        ]);
    }

    /**
     * Update company car
     *
     * @param UpdateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(UpdateRequest $request)
    {
        $this->car->setModel($request->getCar());
        $car = $this->car->save($request->ruleWithCompany());

        return apiResponse($this->car, [
            'car' => new CarResource($car),
        ]);
    }

    /**
     * Set car image
     *
     * @param SetImageRequest $request
     * @return Response
     */
    public function setCarImage(SetImageRequest $request)
    {
        $this->car->setModel($request->getCar());
        $car = $this->car->setCarImage($request->car_image);

        return apiResponse($this->car, [
            'car' => new CarResource($car),
        ]);
    }

    /**
     * Delete car
     *
     * @param FindRequest $request
     * @return Response
     */
    public function delete(FindRequest $request)
    {
        $this->car->setModel($request->getCar());
        $this->car->delete(strtobool($request->input('force')));

        return apiResponse($this->car);
    }

    /**
     * Restore car
     *
     * @param RestoreRequest $request
     * @return Response
     */
    public function restore(RestoreRequest $request)
    {
        $this->car->setModel($request->getTrashedCar());
        $car = $this->car->restore();

        return apiResponse($this->car, [
            'car' => new CarResource($car),
        ]);
    }
}
