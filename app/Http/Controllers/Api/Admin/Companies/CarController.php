<?php

namespace App\Http\Controllers\Api\Admin\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Cars\PopulateCompanyCarsRequest as PopulateRequest;
use App\Http\Resources\CarResource;

use App\Repositories\CarRepository;

class CarController extends Controller
{
    private $car;

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
}
