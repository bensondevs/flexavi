<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{
    Company,
    Employee,
    CarRegisterTime,
    CarRegisterTimeEmployee as AssignedEmployee
};

use App\Enums\CarRegisterTimeEmployee\PassangerType;

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
        return $this->afterMaking(function (AssignedEmployee $assignedEmployee) {
            if (! $company = $assignedEmployee->company) {
                $company = Company::inRandomOrder()->first() ?:
                    Company::factory()->create();
                $assignedEmployee->company()->associate($company);
            }

            if (! $time = $assignedEmployee->carRegisterTime) {
                $time = CarRegisterTime::factory()->for($company)->create();
                $assignedEmployee->carRegisterTime()->associate($time);
            }

            if (! $employee = $assignedEmployee->employee) {
                $employee = Employee::factory()->for($company)->create();
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
        $faker = $this->faker;

        return [
            'passanger_type' => rand(PassangerType::Driver, PassangerType::Passanger),
        ];
    }

    /**
     * Indicate that the model that has Driver Type.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
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
