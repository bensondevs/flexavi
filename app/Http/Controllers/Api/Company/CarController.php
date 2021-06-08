<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Cars\SaveCarRequest;
use App\Http\Requests\Cars\FindCarRequest;
use App\Http\Requests\Cars\SetCarImageRequest;
use App\Http\Requests\Cars\PopulateCompanyCarsRequest;

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

    public function companyCars(PopulateCompanyCarsRequest $request)
    {
        $options = $request->options();

    	$cars = $this->car->all($options);
        $cars = $this->car->paginate($options['per_page']);
        $cars = CarResource::apiCollection($cars);

    	return response()->json(['cars' => $cars]);
    }

    public function store(SaveCarRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$car = $this->car->save($input);

    	return apiResponse($this->car, ['car' => $car]);
    }

    public function view(FindCarRequest $request)
    {
        $car = $this->car->find($request->input('id'));

        return response()->json(['car' => $car]);
    }

    public function validateInsurance(FindCarRequest $request)
    {
        $this->car->setModel($request->getCar());
        $car = $this->car->validateInsurance();

        return apiResponse($this->car, ['car' => $car]);
    }

    public function update(SaveCarRequest $request)
    {
    	$this->car->setModel($request->getCar());
    	$car = $this->car->save($request->onlyInRules());

    	return apiResponse($this->car, ['car' => $car]);
    }

    public function setCarImage(SetCarImageRequest $request)
    {
        $this->car->setModel($request->getCar());
        $this->car->setCarImage($request->file('car_image'));

        return apiResponse(
            $this->car, 
            $this->car->getModel()
        );
    }

    public function delete(FindCarRequest $request)
    {
    	$this->car->setModel($request->getCar());
    	$this->car->delete();

    	return apiResponse($this->car, ['car' => $this->car->getModel()]);
    }
}
