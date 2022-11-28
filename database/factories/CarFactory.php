<?php

namespace Database\Factories;

use App\Enums\Car\CarStatus;
use App\Models\{Car\Car, Company\Company};
use App\Traits\FactoryDeletedState;
use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\{Storage};

class CarFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        // create upload directory with the right permission
        $path = Storage::path('cars');
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        return $this->afterMaking(function (Car $car) {
            if (!$car->company_id) {
                $company = Company::factory()->create();
                $car->company()->associate($company);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new Fakecar($this->faker));
        $name = $this->faker->vehicleBrand;
        return [
            'brand' => $this->faker->company,
            'model' => $name,
            'year' => $this->faker->year,
            'status' => CarStatus::Free,
            'insured' => $this->faker->boolean,
            'car_name' => "$name Fleet",
            'car_license' => $this->faker->vehicleRegistration,
            'car_image_path' => Car::placeholder(),
        ];
    }

    /**
     * Indicate that the model's that free.
     *
     * @return Factory
     */
    public function free()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => CarStatus::Free,
            ];
        });
    }

    /**
     * Indicate that the model's that out.
     *
     * @return Factory
     */
    public function out()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => CarStatus::Out,
            ];
        });
    }
}
