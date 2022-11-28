<?php

namespace Database\Factories;

use App\Enums\User\UserSocialiteAccountType;
use App\Models\User\User;
use App\Models\User\UserSocialiteAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserSocialiteAccountFactory extends Factory
{
    protected $model = UserSocialiteAccount::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (UserSocialiteAccount $userSocialiteAccount) {
            if (!$userSocialiteAccount->user_id) {
                $user = User::factory()->create();
                $userSocialiteAccount->user()->associate($user);
            }
        });
    }

    public function definition(): array
    {
        return [
            'type' => UserSocialiteAccountType::getRandomValue(),
            'vendor_user_id' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }


    /**
     * Indicate that the model's that Google.
     *
     * @return Factory
     */
    public function google(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => UserSocialiteAccountType::Google,
            ];
        });
    }

}
