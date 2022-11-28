<?php

namespace Database\Factories;

use App\Models\{PostIt\PostIt, PostIt\PostItAssignedUser, User\User};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostItAssignedUserFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostItAssignedUser::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function factory()
    {
        return $this->afterMaking(function (PostItAssignedUser $pivot) {
            if (!$pivot->post_it_id) {
                $postIt = PostIt::factory()->create();
                $pivot->postIt()->associate($postIt);
            }
            if (!$pivot->user_id) {
                $user = User::factory()
                    ->owner()
                    ->create();
                $pivot->user()->associate($user);
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
