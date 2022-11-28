<?php

namespace Database\Factories;

use App\Enums\Work\WorkStatus as Status;
use App\Models\{Appointment\Appointment, Company\Company, Work\Work};
use App\Traits\FactoryDeletedState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            if (!$work->company_id) {
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
        $unitPrice = $this->faker->randomNumber(4, true);
        $includeTax = $this->faker->numberBetween(0, 1);
        $taxPercentage = 0;
        if ($includeTax) {
            $taxPercentage = rand(20, 50);
        }
        $totalPrice = $unitPrice + $unitPrice * ($taxPercentage / 100);

        return [
            'status' => Status::Created,
            'quantity' => $this->faker->randomNumber(2, true),
            'description' => $this->faker->word,
            'unit_price' => $unitPrice,
            'include_tax' => $includeTax,
            'tax_percentage' => $taxPercentage,
            'total_price' => $totalPrice,
            'total_paid' => $this->faker->numberBetween(0, $totalPrice),
            'note' => $this->faker->word,
        ];
    }

    /**
     * Indicate that the work created will have status of Created
     *
     * @return Factory
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
     * @return Factory
     */
    public function inProcess()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::InProcess,
                'executed_at' => Carbon::now(),
            ];
        });
    }

    /**
     * Indicate that the work created will have status of Finished
     *
     * @return Factory
     */
    public function finished()
    {
        return $this->state(function (array $attributes) {
            if (!isset($attributes['company_id'])) {
                $company = Company::factory()->create();
                $attributes['company_id'] = $company->id;
            }
            $company = Company::findOrFail($attributes['company_id']);
            $appointment = Appointment::factory()
                ->for($company)
                ->create();

            return [
                'status' => Status::Finished,
                'finished_at' => Carbon::now(),
                'finished_at_appointment_id' => $appointment->id,
            ];
        });
    }

    /**
     * Indicate that the work created will have status of Unfinished
     *
     * @return Factory
     */
    public function unfinished()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Unfinished,
                'unfinished_at' => Carbon::now(),
            ];
        });
    }
}
