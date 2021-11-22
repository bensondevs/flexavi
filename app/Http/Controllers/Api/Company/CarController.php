<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Cars\{
    SaveCarRequest as SaveRequest,
    FindCarRequest as FindRequest,
    RestoreCarRequest as RestoreRequest,
    SetCarImageRequest as SetImageRequest,
    PopulateCompanyCarsRequest as PopulateRequest
};

use App\Http\Resources\CarResource;

use App\Models\Company;
use App\Repositories\{ CarRepository, CarRegisterTime };

class CarController extends Controller
{
    /**
     * Car repository container variable
     * 
     * @var \App\Models\Car
     */
    private $car;

    /**
     * Controller constructor method
     * 
     * @param CarRepository  $car
     * @return void
     */
    public function __construct(CarRepository $car) 
    {
    	$this->car = $car;
    }

    /**
     * Populate company cars
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyCars(PopulateRequest $request)
    {
        $options = $request->options();

    	$cars = $this->car->all($options);
        $cars = $this->car->paginate();
        $cars = CarResource::apiCollection($cars);

    	return response()->json(['cars' => $cars]);
    }

    /**
     * Populate company free cars
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function freeCars(PopulateRequest $request)
    {
        $options = $request->options();

        $cars = $this->car->freeCars($options);
        $cars = $this->car->paginate();
        $cars = CarResource::apiCollection($cars);

        return response()->json(['cars' => $cars]);
    }

    /**
     * Populate company soft-deleted cars
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedCars(PopulateRequest $request)
    {
        $options = $request->options();

        $cars = $this->car->trasheds($options);
        $cars = $this->car->paginate();
        $cars = CarResource::apiCollection($cars);

        return response()->json(['cars' => $cars]);
    }

    /**
     * Store company car
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$car = $this->car->save($input);

    	return apiResponse($this->car);
    }

    /**
     * View company car
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $car = $request->getCar();
        $car = new CarResource($car);
        return response()->json(['car' => $car]);
    }

    /**
     * Validate car insurance
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function validateInsurance(FindRequest $request)
    {
        $car = $request->getCar();
        $car = $this->car->setModel($car);
        $car = $this->car->validateInsurance();

        return apiResponse($this->car);
    }

    /**
     * Update company car
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $car = $request->getCar();
    	$car = $this->car->setModel($car);

        $input = $request->onlyInRules();
    	$car = $this->car->save($input);

    	return apiResponse($this->car);
    }

    /**
     * Set car image
     * 
     * @param SetImageRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function setCarImage(SetImageRequest $request)
    {
        $car = $request->getCar();
        $car = $this->car->setModel($car);

        $image = $request->car_image;
        $car = $this->car->setCarImage($image);

        return apiResponse($this->car, ['car' => $car]);
    }

    /**
     * Delete car
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(FindRequest $request)
    {
        $car = $request->getCar();
    	$this->car->setModel($car);

        $force = strtobool($request->input('force'));
    	$this->car->delete($force);

    	return apiResponse($this->car);
    }

    /**
     * Restore car
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $car = $request->getTrashedCar();
        $car = $this->car->setModel($car);
        $car = $this->car->restore();

        return apiResponse($this->car, ['car' => $car]);
    }
}
