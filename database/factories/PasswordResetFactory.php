<?php

namespace Database\Factories;

use App\Enums\Auth\ResetPasswordType;
use App\Models\User\PasswordReset;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasswordResetFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PasswordReset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'email' => $user->email,
            'phone' => $user->phone,
            'reset_via' => ResetPasswordType::getRandomValue(),
            'token' => randomToken(6),
            'created_at' => now(),
            'expired_at' => now()->addMinutes(5),
        ];
    }
}
