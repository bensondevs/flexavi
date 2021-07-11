<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Cars\SaveCarRequest as SaveRequest;
use App\Http\Requests\Cars\FindCarRequest as FindRequest;
use App\Http\Requests\Cars\RestoreCarRequest as RestoreRequest;
use App\Http\Requests\Cars\SetCarImageRequest as SetImageRequest;
use App\Http\Requests\Cars\PopulateCompanyCarsRequest as PopulateRequest;

use App\Http\Resources\CarResource;

use App\Models\Company;

use App\Repositories\CarRepository;

class CarController extends Controller
{
    protected $car;

    public function __construct(CarRepository $car)
    {
    	$this->car = $car;
    }

    public function companyCars(PopulateRequest $request)
    {
        $options = $request->options();

    	$cars = $this->car->all($options);
        $cars = $this->car->paginate();
        $cars = CarResource::apiCollection($cars);

    	return response()->json(['cars' => $cars]);
    }

    public function freeCars(PopulateRequest $request)
    {
        $options = $request->options();

        $cars = $this->car->freeCars($options);
        $cars = $this->car->paginate();
        $cars = CarResource::apiCollection($cars);

        return response()->json(['cars' => $cars]);
    }

    public function trashedCars(PopulateRequest $request)
    {
        $options = $request->options();

        $cars = $this->car->trasheds($options);
        $cars = $this->car->paginate();
        $cars = CarResource::apiCollection($cars);

        return response()->json(['cars' => $cars]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$car = $this->car->save($input);

    	return apiResponse($this->car, ['car' => $car]);
    }

    public function view(FindRequest $request)
    {
        $car = $request->getCar();
        return response()->json(['car' => $car]);
    }

    public function validateInsurance(FindRequest $request)
    {
        $car = $request->getCar();
        $car = $this->car->setModel($car);
        $car = $this->car->validateInsurance();

        return apiResponse($this->car, ['car' => $car]);
    }

    public function update(SaveRequest $request)
    {
        $car = $request->getCar();
    	$car = $this->car->setModel($car);

        $input = $request->onlyInRules();
    	$car = $this->car->save($input);

    	return apiResponse($this->car, ['car' => $car]);
    }

    public function setCarImage(SetImageRequest $request)
    {
        $car = $request->getCar();
        $car = $this->car->setModel($car);

        $image = $request->file('car_image');
        $car = $this->car->setCarImage($image);

        return apiResponse($this->car, ['car' => $car]);
    }

    public function delete(FindRequest $request)
    {
        $car = $request->getCar();
    	$this->car->setModel($car);

        $force = strtobool($request->input('force'));
    	$this->car->delete($force);

    	return apiResponse($this->car);
    }

    public function restore(RestoreRequest $request)
    {
        $car = $request->getTrashedCar();
        $car = $this->car->setModel($car);
        $car = $this->car->restore();

        return apiResponse($this->car, ['car' => $car]);
    }
}
