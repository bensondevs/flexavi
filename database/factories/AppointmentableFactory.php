<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    Appointment,
    Worklist,
    Workday,
    Appointmentable
};

class AppointmentableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointmentable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        $appointmentableClasses = [
            Worklist::class,
            Workday::class,
        ];
        $appointmentableClass = $faker->randomElement($appointmentableClasses);
        $appointmentable = $appointmentableClass::factory()->create();

        $appointment = Appointment::factory()->create(['company_id' => $appointmentable->company_id]);

        return [
            'id' => $faker->uuid(),
            'order_index' => 1,
            'appointmentable_id' => $appointmentable->id,
            'appointmentable_type' => get_class($appointmentable),
            'appointment_id' => $appointment->id,
        ];
    }
}
