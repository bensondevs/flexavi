<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ User, Company, PostIt };

class PostItFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostIt::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function factory()
    {
        return $this->afterMaking(function (PostIt $postIt) {
            if (! $postIt->company_id) {
                $company = Company::factory()->create();
                $postIt->company()->associate($company);
            }

            if (! $postIt->user_id) {
                $user = User::factory()->create();
                $postIt->user()->associate($user);
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
            'content' => $this->faker->word(),
        ];
    }
}
