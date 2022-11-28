<?php

namespace Database\Factories;

use App\Models\{Company\Company};
use App\Models\HelpDesk\HelpDesk;
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class HelpDeskFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HelpDesk::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (HelpDesk $helpDesk) {
            if (!isset($helpDesk->company_id)) {
                $company = Company::factory()->create();
                $helpDesk->company()->associate($company);
            }

            return $this;
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
            'title' => $this->faker->title,
            'content' => $this->faker->text,
            'created_at' => now()->copy()->subDays(rand(1, 10)),
            'updated_at' => now()->copy()->subDays(rand(1, 10)),
        ];
    }
}
