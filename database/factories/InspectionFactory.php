<?php

namespace Database\Factories;

use App\Models\{Appointment\Appointment, Company\Company, Inspection\Inspection};
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Inspection::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Inspection $inspection) {
            if (!$inspection->company_id) {
                $company = Company::factory()->create();
                $inspection->company()->associate($company);
            }

            if (!$inspection->appointment_id) {
                $appointment = Appointment::factory()
                    ->for($inspection->company)
                    ->create();
                $inspection->appointment()->associate($appointment);
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
        return [];
    }
}
