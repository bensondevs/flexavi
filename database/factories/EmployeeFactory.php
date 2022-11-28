<?php

namespace Database\Factories;

use App\Enums\Employee\{EmployeeType, EmploymentStatus};
use App\Models\{Company\Company, Employee\Employee, User\User};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Employee $employee) {
            if (!$employee->company_id) {
                $company = Company::factory()->create();
                $employee->company_id = $company->id;
            }

            if (!$employee->user_id) {
                $user = User::factory()->create();
                $employee->user_id = $user->id;
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
            'title' => $this->faker->word,
            'employee_type' => $this->faker->randomElement([
                EmployeeType::Administrative,
                EmployeeType::Roofer,
            ]),
            'employment_status' => $this->faker->randomElement([
                EmploymentStatus::Active,
                EmploymentStatus::Inactive,
            ]),
        ];
    }

    /**
     * Indicate that the model that has Administrative type.
     *
     * @return Factory
     */
    public function administrative(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'employee_type' => EmployeeType::Administrative,
            ];
        });
    }

    /**
     * Indicate that the model that has Roofer type.
     *
     * @return Factory
     */
    public function roofer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'employee_type' => EmployeeType::Roofer,
            ];
        });
    }

    /**
     * Indicate that the model that has status of Active.
     *
     * @rreturn Factory
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'employment_status' => EmploymentStatus::Active,
            ];
        });
    }

    /**
     * Indicate that the model that has status of Inactive.
     *
     * @rreturn Factory
     */
    public function inactive()
    {
        return $this->state(function ($attributes) {
            return [
                'employment_status' => EmploymentStatus::Inactive,
            ];
        });
    }
}
