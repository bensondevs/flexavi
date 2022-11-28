<?php

namespace Database\Factories;

use App\Models\{Car\Car, Car\CarRegisterTime, Company\Company, Worklist\Worklist};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarRegisterTimeFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarRegisterTime::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (CarRegisterTime $time) {
            if (!$time->company_id) {
                $company = Company::factory()->create();
                $time->company()->associate($company);
            }
            if (!$time->car_id) {
                $company = $time->company;
                $car = Car::factory()
                    ->for($company)
                    ->create();
                $time->car()->associate($car);
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
        return [
            'should_out_at' => $this->faker->datetime,
            'should_return_at' => $this->faker->datetime,
        ];
    }

    /**
     * Indicate that the model's that assigned to worklist.
     *
     * @return Factory
     */
    public function assignedToWorklist($worklist = null)
    {
        if (!$worklist) {
            $company = Company::factory()->create();
            $worklist = Worklist::factory()->create([
                'company_id' => $company->id,
            ]);
        }

        return $this->state(function (array $attributes) use ($worklist) {
            return [
                'worklist_id' => $worklist->id,
            ];
        });
    }

    /**
     * Indicate that the model's that hasn't been marked out.
     *
     * @return Factory
     */
    public function notMarkedOut()
    {
        return $this->state(function (array $attributes) {
            return [
                'marked_out_at' => null,
            ];
        });
    }

    /**
     * Indicate that the model's that has been marked out.
     *
     * @return Factory
     */
    public function markedOut()
    {
        return $this->state(function (array $attributes) {
            return [
                'marked_out_at' => $this->faker->datetime,
            ];
        });
    }

    /**
     * Indicate that the model's that hasn't been marked returned.
     *
     * @return Factory
     */
    public function notMarkedReturned()
    {
        return $this->state(function (array $attributes) {
            return [
                'marked_return_at' => null,
            ];
        });
    }

    /**
     * Indicate that the model's that has been marked returned.
     *
     * @return Factory
     */
    public function markedReturned()
    {
        return $this->state(function (array $attributes) {
            return [
                'marked_return_at' => $this->faker->datetime,
            ];
        });
    }
}
