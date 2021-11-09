<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Costable, Cost, Appointment, Worklist, Workday };

class CostableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Costable::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        $faker = $this->faker;
        return $this->afterMaking(function (Costable $costable) use ($faker) {
            if (! $costable->company_id) {
                $company = Company::inRandomOrder()->first();
                $costable->company()->associate($company);
            }

            if (! $costable->cost_id) {
                $cost = Cost::factory()->for($costable->company)->create();
                $costable->cost()->associate($cost);
            }

            if (! $costable->costable_id) {
                $types = [Appointment::class, Worklist::class, Workday::class];
                $selectedType = $faker->randomElement($types);
                $assignable = $selectedType::factory()->for($costable->company)->create();
                $costable->costable()->associate($assignable);
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
            //
        ];
    }

    /**
     * Indicate that the model's that has type of appointment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function appointment(Appointment $appointment)
    {
        return $this->state(function (array $attributes) use ($appointment) {
            return [
                'company_id' => $appointment->company_id,
                'costable_id' => $appointment->id,
                'costable_type' => Appointment::class,
            ];
        });
    }

    /**
     * Indicate that the model's that has type of worklist.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function worklist(Worklist $worklist)
    {
        return $this->state(function (array $attributes) use ($worklist) {
            return [
                'company_id' => $worklist->company_id,
                'costable_id' => $worklist->id,
                'costable_type' => Worklist::class,
            ];
        });
    }

    /**
     * Indicate that the model's that has type of workday.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function workday(Workday $workday)
    {
        return $this->state(function (array $attributes) use ($workday) {
            return [
                'company_id' => $workday->company_id,
                'costable_id' => $workday->id,
                'costable_type' => Workday::class,
            ];
        });
    }
}
