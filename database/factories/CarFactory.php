<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Car, Company };

use App\Enums\Car\CarStatus;

use Faker\Provider\Fakecar;

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
        return $this->afterMaking(function (Car $car) {
            if (! $car->company_id) {
                $company = Company::inRandomOrder()->first() ?:
                    Company::factory()->create();
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
        $faker = $this->faker;
        $faker->addProvider(new Fakecar($faker));

        return [
            'brand' => $faker->company,
            'model' => $faker->vehicleBrand,
            'year' => $faker->year,
            'car_name' => $faker->vehicleBrand,
            'car_license' => $faker->vehicleRegistration,
            'insured' => $faker->boolean,
            'status' => CarStatus::Free,
            'car_image_path' => $this->faker->image(
                storage_path('app/public/uploads/cars'), 
                400, 
                300, 
                null, 
                false
            )
        ];
    }

    /**
     * Indicate that the model's that free.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
