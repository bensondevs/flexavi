<?php

namespace Database\Factories;

use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationItem;
use App\Models\WorkService\WorkService;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuotationItem::class;


    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (QuotationItem $quotationItem) {
            if (!$quotationItem->quotation_id) {
                $quotation = Quotation::factory()->create();
                $quotationItem->quotation()->associate($quotation);
            }
            if (!$quotationItem->work_service_id) {
                $workService = WorkService::factory()->for($quotationItem->quotation->company)->create();
                $quotationItem->workService()->associate($workService);
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
            'unit_price' => $this->faker->randomFloat(),
            'amount' => $this->faker->randomNumber(),
            'total' => $this->faker->randomFloat(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
