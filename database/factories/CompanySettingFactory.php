<?php

namespace Database\Factories;

use App\Models\Setting\CompanySetting;
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanySetting>
 */
class CompanySettingFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanySetting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (CompanySetting $companySetting) {
            return $this;
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'auto_subs_same_plan_while_ends' => rand(0, 1),
            'invoicing_address_same_as_visiting_address' => rand(0, 1),
        ];
    }
}
