<?php

namespace Database\Factories;

use App\Enums\Setting\EmployeeSetting\EmployeeInvitationExpiry;
use App\Models\Setting\EmployeeSetting;
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeSetting>
 */
class EmployeeSettingFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeSetting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (EmployeeSetting $employeeSetting) {
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
            'pagination' => $this->faker->randomElement([10,20,50,100]),
            'use_initials_when_dont_have_avatar' => rand(0, 1),
            'invitation_expiry' => EmployeeInvitationExpiry::getRandomValue(),
        ];
    }
}
