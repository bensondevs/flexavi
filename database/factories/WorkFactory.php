<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Work, Company, Workable };
use App\Enums\Work\WorkStatus as Status;

class WorkFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Work::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Work $work) {
            if (! $work->company_id) {
                $company = Company::factory()->create();
                $work->company()->associate($company);
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

        $unitPrice = $faker->randomNumber(3, false);
        $includeTax = rand(0, 1);
        $taxPercentage = 0;
        if ($includeTax) {
            $taxPercentage = rand(20, 50);
        }

        $totalPrice = $unitPrice + ($unitPrice * ($taxPercentage / 100));

        return [
            'status' => Status::Created,
            'quantity' => $faker->randomNumber(),
            'description' => $faker->word(),
            'unit_price' => $unitPrice,
            'include_tax' => $includeTax,
            'tax_percentage' => $taxPercentage,
            'total_price' => $totalPrice,
            'total_paid' => rand(0, $totalPrice),
            'note' => $faker->word(),
        ];
    }

    /**
     * Indicate that the work created will have status of Created
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function created()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Created,
            ];
        });
    }

    /**
     * Indicate that the work created will have status of InProcess
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inProcess()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::InProcess,
                'executed_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the work created will have status of Finished
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function finished()
    {
        return $this->state(function (array $attributes) {
            if (! isset($attributes['company_id'])) {
                $company = Company::factory()->create();
                $attributes['company_id'] = $company->id;
            }

            $company = Company::findOrFail($attributes['company_id']);
            $appointment = Appointment::factory()->for($company)->create();

            return [
                'status' => Status::Finished,
                'finished_at' => now(),
                'finished_at_appointment_id' => $appointment->id,
            ];
        });
    }

    /**
     * Indicate that the work created will have status of Unfinished
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unfinished()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Unfinished,
                'unfinished_at' => now(),
            ];
        });
    }
}
