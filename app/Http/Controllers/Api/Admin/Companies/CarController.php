<?php

namespace App\Http\Controllers\Api\Admin\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Cars\PopulateCompanyCarsRequest;

use App\Repositories\CarRepository;

class CarController extends Controller
{
    private $car;

    public function __construct(CarRepository $car)
    {
    	$this->car = $car;
    }

    public function companyCars(PopulateCompanyCarsRequest $request)
    {
    	$cars = $this->car->companyCars();
    }
}
