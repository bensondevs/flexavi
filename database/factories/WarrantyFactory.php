<?php

namespace Database\Factories;

use App\Enums\Warranty\WarrantyStatus as Status;
use App\Models\{Appointment\Appointment, Company\Company, Warranty\Warranty, Warranty\WarrantyWork};
use App\Traits\FactoryDeletedState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarrantyFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Warranty $warranty) {
            if (!$warranty->company_id) {
                $company = Company::factory()->create();
                $warranty->company()->associate($company);
            }
            if (!$warranty->appointment_id) {
                $company = Company::findOrFail($warranty->company_id);
                $appointment = Appointment::factory()
                    ->for($company)
                    ->create();
                $warranty->appointment()->associate($appointment);
            }
            if (!$warranty->for_appointment_id) {
                $company = Company::findOrFail($warranty->company_id);
                $appointment = Appointment::factory()
                    ->for($company)
                    ->create();
                $warranty->forAppointment()->associate($appointment);
            }
        })->afterCreating(function (Warranty $warranty) {
            if (!$warranty->warrantyWorks()->exists()) {
                $warranty->warrantyWorks = WarrantyWork::factory()
                    ->for($warranty)
                    ->count(3)
                    ->create();
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
        $structure = [
            'status' => $this->faker->randomElement([
                Status::Created,
                Status::Unfinished,
            ]),
            'problem_description' => $this->faker->text,
            'fixing_description' => $this->faker->text,
            'internal_note' => $this->faker->text,
            'customer_note' => $this->faker->text,
            'amount' => 0,
            'paid_amount' => 0,
            'created_at' => Carbon::now(),
        ];
        if ($structure['status'] >= Status::InProcess) {
            $structure['in_process_at'] = Carbon::now()->addDays(rand(1, 3));
        }
        if ($structure['status'] == Status::Finished) {
            $structure['finished_at'] = $structure['in_process_at']->addDays(
                $this->faker->numberBetween(0, 2)
            );
        } elseif ($structure['status'] == Status::Unfinished) {
            $structure['unfinished_at'] = $structure['in_process_at']->addDays(
                $this->faker->numberBetween(0, 2)
            );
        }

        return $structure;
    }

    /**
     * Indicate that the model's that has status of created.
     *
     * @return Factory
     */
    public function created()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Created,
                'created_at' => Carbon::now(),
            ];
        });
    }

    /**
     * Indicate that the model's that has status of in process.
     *
     * @return Factory
     */
    public function inProcess()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::InProcess,
                'created_at' => Carbon::now(),
                'in_process_at' => Carbon::now()->addHours(rand(1, 3)),
            ];
        });
    }

    /**
     * Indicate that the model's that has status of finished.
     *
     * @return Factory
     */
    public function finished()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Finished,
                'created_at' => Carbon::now(),
                'in_process_at' => Carbon::now()->addHours(rand(1, 3)),
                'finished_at' => Carbon::now()->addHours(3, 5),
            ];
        });
    }

    /**
     * Indicate that the model's that has status of unfinished.
     *
     * @return Factory
     */
    public function unfinished()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Unfinished,
                'created_at' => Carbon::now(),
                'in_process_at' => Carbon::now()->addHours(rand(1, 3)),
                'unfinished_at' => Carbon::now()->addHours(3, 5),
            ];
        });
    }
}
