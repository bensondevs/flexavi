<?php

namespace Database\Factories;

use App\Models\FAQ\FrequentlyAskedQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class FrequentlyAskedQuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FrequentlyAskedQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];
    }
}
