<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    Company,
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
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Appointmentable $appointmentable) {
            if (! $appointmentable->company_id) {
                $company = Company::factory()->create();
                $appointmentable->company()->associate($company);
            }

            if (! $appointmentable->appointment_id) {
                $appointment = Appointment::factory()->for($appointmentable->company)->create();
                $appointmentable->appointment()->associate($appointment);
            }

            if (! $appointmentable->appointmentable_id) {
                $types = [Worklist::class, Workday::class];
                $selectedType = $this->faker->randomElement($types);
                $attachable = $selectedType::factory()->create(['company_id' => $appointmentable->company_id]);
                $appointmentable->{get_lower_class($selectedType)}()->attach($attachable);
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
            'id' => $faker->uuid(),
            'order_index' => 1,
        ];
    }

    /**
     * Set order index of appointmentable.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function index(int $index)
    {
        return $this->state(function (array $attributes) {
            return ['order_index' => $index];
        });
    }

    /**
     * Indicate model appointment has relationship with worklist.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function worklist($worklist = null)
    {
        return $this->state(function (array $attributes) use ($worklist) {
            if (! $worklist) {
                $worklist = Worklist::factory()->create();
            }

            return [
                'appointmentable_type' => Worklist::class,
                'appointmentable_id' => $worklist->id,
            ];
        });
    }
}
