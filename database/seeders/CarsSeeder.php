<?php

namespace Database\Seeders;

use App\Enums\Car\CarStatus;
use App\Models\{Car\Car, Company\Company};
use Faker\Generator;
use Faker\Provider\Fakecar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{File, Storage};

class CarsSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = app(Generator::class);
        $this->faker->addProvider(new Fakecar($this->faker));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create upload directory with the right permission
        $path = Storage::path('cars');
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        // populate data & image
        foreach (Company::all() as $company) {
            for ($index = 0; $index < 5; $index++) {
                $name = $this->faker->vehicleBrand;
                Car::create([
                    'company_id' => $company->id,
                    'brand' => $this->faker->company,
                    'model' => $name,
                    'year' => $this->faker->year,
                    'status' => CarStatus::Free,
                    'insured' => $this->faker->boolean,
                    'car_name' => "$name Fleet",
                    'car_license' => $this->faker->vehicleRegistration,
                    'car_image_path' => Car::placeholder(),
                    'apk' => now()->addYears(rand(1, 3))->toDateTimeString()
                ]);
            }
        }
    }
}
