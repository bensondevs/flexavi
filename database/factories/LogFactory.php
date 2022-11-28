<?php

namespace Database\Factories;

use App\Models\Log\Log;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class LogFactory extends Factory
{


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Log::class;
    /**
     * Var of subject and action
     *
     * @var string
     */
    private string $action;
    private string $subject;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => generateUuid(),
            'created_at' => now()->subDays(rand(0, 10)),
            'updated_at' => $this->faker->randomElement([
                null, now()->addDays(rand(1, 3))
            ]),
        ];
    }

    /**
     * Set Log Causer
     *
     * @param User $causerable
     * @return Factory
     */
    public function causer($causerable)
    {
        return $this->state(function (array $attributes) use ($causerable) {
            return [
                'causer_type' => get_class($causerable),
                'causer_id' => $causerable->id,
                'company_id' => $causerable->role_model->company_id ?? null,
            ];
        });
    }

    /**
     * Set Log Subject
     *
     * @param Model $subject
     * @return Factory
     */
    public function subject(Model $subject)
    {
        return $this->state(function (array $attributes) use ($subject) {
            $this->subject = get_class($subject);
            return [
                'subject_type' => $this->subject,
                'subject_id' => $subject->id,
            ];
        });
    }

    /**
     * Set Log Name
     *
     * @param string $name
     * @return Factory
     */
    public function name(string $name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'log_name' => $name,
            ];
        });
    }
}
