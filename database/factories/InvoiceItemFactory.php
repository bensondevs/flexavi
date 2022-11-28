<?php

namespace Database\Factories;

use App\Models\{Invoice\Invoice, Invoice\InvoiceItem, WorkService\WorkService};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceItem::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (InvoiceItem $item) {
            if (!$item->invoice_id) {
                $invoice = Invoice::factory()->create([]);
                $item->invoice()->associate($invoice);
            }

            if (!$item->work_service_id) {
                $workService = WorkService::factory()->create([]);
                $item->workService()->associate($workService);
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
            'tax_percentage' => rand(5, 15),
            'amount' => $this->faker->numberBetween(10, 12),
            'unit_price' => $this->faker->randomFloat(2, 10, 100),
            'total' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
