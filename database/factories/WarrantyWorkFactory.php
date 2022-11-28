<?php

namespace Database\Factories;

use App\Enums\Warranty\WarrantyStatus as Status;
use App\Models\{Warranty\Warranty, Warranty\WarrantyWork, Work\Work};
use App\Traits\FactoryDeletedState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarrantyWorkFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (WarrantyWork $warrantyWork) {
            if (!$warrantyWork->warranty_id) {
                $warranty = Warranty::factory()->create();
                $warrantyWork->warranty()->associate($warranty);
            }
            if (!$warrantyWork->work_id) {
                $work = Work::factory()->create();
                $warrantyWork->work()->associate($work);
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
            'status' => $this->faker->randomElement([
                Status::Created,
                Status::Unfinished,
            ]),
            'amount' => $this->faker->numberBetween(0, 100),
            'note' => $this->faker->word,
        ];
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
