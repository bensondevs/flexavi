<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Car;

use App\Repositories\CarRepository;
use App\Repositories\CompanyRepository;

class CarsSeeder extends Seeder
{
	private $car;
	private $company;

	public function __construct(
		CarRepository $car,
		CompanyRepository $company
	)
	{
		$this->car = $car;
		$this->company = $company;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = $this->company->all();

        foreach ($companies as $key => $company) {
        	for ($index = 0; $index < rand(5, 20); $index++) {
        		$brand = (['Mercedes', 'Toyota', 'Mitsubishi'])[rand(0, 2)];
        		$model = (['A', 'B', 'C', 'D'])[rand(0, 3)];

        		$this->car->save([
        			'company_id' => $company->id,
        			'car_image_url' => 'https://dummyimage.com/300/09f/fff.png',
        			'brand' => $brand,
        			'model' => $model,
        			'year' => rand(2005, 2015),
        			'car_name' => $company->company_name . ' Car ' . $brand . ' ' . $model,
        			'car_license' => rand(100000, 999999),
        			'insured' => (bool) rand(0, 1),
        			'status' => 'free',
        		]);
        		$this->car->setModel(new Car);
        	}
        }
    }
}
