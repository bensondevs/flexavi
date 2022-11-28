<?php

namespace Database\Factories;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceLog;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceLog::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (InvoiceLog $log) {
            if (!$log->invoice_id) {
                $invoice = Invoice::factory()->create();
                $log->invoice()->associate($invoice);
            }
            if (!$log->actor_id) {
                $owner = User::factory()->owner()->create();
                $log->actor()->associate($owner);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'message' => [
                'en' => $this->faker->sentence(),
                'nl' => $this->faker->sentence(),
            ],
        ];
    }
}
