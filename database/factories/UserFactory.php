<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Owner;
use App\Models\RegisterInvitation;

use App\Enums\User\UserIdCardType as CardType;

class UserFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if (! $user->roles()->exists()) {
                $user->assignRole('owner');
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
            'fullname' => $this->faker->name(),
            'birth_date' => $this->faker->date(),
            'id_card_type' => rand(CardType::NationalIdCard, CardType::DrivingLicense),
            'id_card_number' => $this->faker->numerify('##########'),
            'profile_picture_path' => $this->faker->image(
                storage_path('app/public/uploads/users/profile_pictures'), 
                400, 
                300, 
                null, 
                false
            ),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'registration_code' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the model's role is owner.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function owner()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('owner');
            $user->owner()->save(Owner::factory()->make());
        });
    }

    /**
     * Indicate that the model's registered with registration code.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function useCode()
    {
        $userEmail = $this->definition()['email'];
        $registerInvitation = RegisterInvitation::inRandomOrder()->first() ?: 
            RegisterInvitation::factory()->create([
                'invited_email' => $userEmail,
            ])->create();
        return $this->state(function (array $attributes) use ($registerInvitation) {
            return [
                'registration_code' => $registerInvitation->registration_code,
            ];
        });
    }
}
