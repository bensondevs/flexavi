<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Car, Company, Worklist, CarRegisterTime };

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
            if (! $time->company_id) {
                $company = Company::factory()->create();
                $time->company()->associate($company);
            }

            if (! $time->car_id) {
                $company = $time->company;
                $car = Car::factory()->for($company)->create();
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
        $faker = $this->faker;

        return [
            'should_out_at' => $faker->datetime,
            'should_return_at' => $faker->datetime,
        ];
    }

    /**
     * Indicate that the model's that assigned to worklist.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function assignedToWorklist($worklist = null)
    {
        if (! $worklist) {
            $companyId = $this->states->company_id;
            $worklist = Worklist::factory()->create(['company_id' => $companyId]);
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
