<?php

namespace Database\Factories;

use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationLog;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuotationLog::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (QuotationLog $quotationLog) {
            if (!$quotationLog->quotation_id) {
                $quotation = Quotation::factory()->create();
                $quotationLog->quotation()->associate($quotation);
            }
            if (!$quotationLog->actor_id) {
                $actor = User::factory()->owner()->create();
                $quotationLog->actor_type = User::class;
                $quotationLog->actor_id = $actor->id;
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
            'log_name' => $this->faker->name(),
            'properties' => json_encode($this->faker->words()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
