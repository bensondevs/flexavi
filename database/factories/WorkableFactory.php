<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ 
    Work, 
    Workable, 
    Appointment, 
    Quotation, 
    SubAppointment 
};

class WorkableFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workable::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Workable $workable) {
            if (! $workable->work_id) {
                $work = Work::factory()->create();
                $workable->work()->associate($work);
            }

            if (! $workable->workable_id) {
                $types = [new Appointment, new Quotation, new SubAppointment];
                $selectedType = $this->faker->randomElement($type);
                $attachable = $selectedType::factory()->create();

                $workable->workable_id = $attachable->id;
                $workable->workable_type = $selectedType;
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
            //
        ];
    }
}
