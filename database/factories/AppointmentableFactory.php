<?php

namespace Database\Factories;

use App\Models\{Appointment\Appointment,
    Appointment\Appointmentable,
    Company\Company,
    Workday\Workday,
    Worklist\Worklist};
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointmentable::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Appointmentable $appointmentable) {
            if (!$appointmentable->company_id) {
                $company = Company::factory()->create();
                $appointmentable->company()->associate($company);
            }
            if (!$appointmentable->appointment_id) {
                $appointment = Appointment::factory()
                    ->for($appointmentable->company)
                    ->create();
                $appointmentable->appointment()->associate($appointment);
            }
            if (!$appointmentable->appointmentable_id) {
                $selectedType = $this->faker->randomElement([
                    new Worklist(),
                    new Workday(),
                ]);
                $attachable = $selectedType
                    ::factory()
                    ->create(['company_id' => $appointmentable->company_id]);
                $appointmentable->appointmentable_id = $attachable->id;
                $appointmentable->appointmentable_type = $selectedType;
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
            'order_index' => 1,
        ];
    }

    /**
     * Set order index of appointmentable.
     *
     * @return Factory
     */
    public function index(int $index)
    {
        return $this->state(function (array $attributes) use ($index) {
            return ['order_index' => $index];
        });
    }

    /**
     * Indicate model appointment has relationship with worklist.
     *
     * @param Worklist|null
     * @return Factory
     */
    public function worklist($worklist = null)
    {
        return $this->state(function (array $attributes) use ($worklist) {
            if (!$worklist) {
                $company = $this->company;
                $worklist = Worklist::factory()->create();
            }

            return [
                'company_id' => $worklist->company_id,
                'appointmentable_type' => Worklist::class,
                'appointmentable_id' => $worklist->id,
            ];
        });
    }
}
