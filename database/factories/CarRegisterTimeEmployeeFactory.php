<?php

namespace Database\Factories;

use App\Enums\CarRegisterTimeEmployee\PassangerType;
use App\Models\{Car\CarRegisterTime,
    Car\CarRegisterTimeEmployee as AssignedEmployee,
    Company\Company,
    Employee\Employee};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarRegisterTimeEmployeeFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssignedEmployee::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (
            AssignedEmployee $assignedEmployee
        ) {
            if (!($company = $assignedEmployee->company)) {
                $company = Company::factory()->create();
                $assignedEmployee->company()->associate($company);
            }
            if (!($time = $assignedEmployee->carRegisterTime)) {
                $time = CarRegisterTime::factory()
                    ->for($company)
                    ->create();
                $assignedEmployee->carRegisterTime()->associate($time);
            }
            if (!($employee = $assignedEmployee->employee)) {
                $employee = Employee::factory()
                    ->for($company)
                    ->create();
                $assignedEmployee->employee()->associate($employee);
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
            'passanger_type' => $this->faker->randomElement([
                PassangerType::Driver,
                PassangerType::Passanger,
            ]),
        ];
    }

    /**
     * Indicate that the model that has Driver Type.
     *
     * @return Factory
     */
    public function driver()
    {
        return $this->state(function (array $attributes) {
            return [
                'passanger_type' => PassangerType::Driver,
            ];
        });
    }

    /**
     * Indicate that the model that has Passanger type.
     *
     * @return Factory
     */
    public function passanger()
    {
        return $this->state(function (array $attributes) {
            return [
                'passanger_type' => PassangerType::Passanger,
            ];
        });
    }
}
