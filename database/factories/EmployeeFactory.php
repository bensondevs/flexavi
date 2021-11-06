<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Employee, User, Company };

use App\Enums\Employee\{ EmployeeType, EmploymentStatus };

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
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        $user = User::inRandomOrder()
            ->whereDoesntHave('employee')
            ->whereDoesntHave('owner')
            ->first() ?: User::factory()->create();
        $company = Company::inRandomOrder()->first() ?:
            Company::factory()->first();
            
        return [
            'user_id' => $user->id,
            'company_id' => $company->id,
            'title' => $this->faker->word,
            'employee_type' => rand(EmployeeType::Administrative, EmployeeType::Roofer),
            'employment_status' => rand(EmploymentStatus::Active, EmploymentStatus::Fired),
        ];
    }

    /**
     * Indicate that the model that has Administrative type.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function administrative()
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function roofer()
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive()
    {
        return $this->state(function ($attributes) {
            return [
                'employment_status' => EmploymentStatus::Inactive,
            ];
        });
    }

    /**
     * Indicate that the model that has status of Fired.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function fired()
    {
        return $this->state(function ($attributes) {
            return [
                'employment_status' => EmploymentStatus::Fired,
            ];
        });
    }
}
