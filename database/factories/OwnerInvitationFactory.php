<?php

namespace Database\Factories;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Models\Company\Company;
use App\Models\Owner\OwnerInvitation;
use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class OwnerInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OwnerInvitation::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (OwnerInvitation $ownerInvitation) {
            if (!$ownerInvitation->company_id) {
                $company = Company::factory()->create();
                $ownerInvitation->company()->associate($company);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'invited_email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->unique()->name,
            'phone' => $this->faker->unique()->phoneNumber,
            'status' => OwnerInvitationStatus::Active,
            'permissions' => Permission::take(rand(10, 20))->inRandomOrder()->get()->pluck('id')->toArray()
        ];
    }

    /**
     * Indicate that the invitation is active (not expired).
     *
     * @return Factory
     */
    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => OwnerInvitationStatus::Active,
                'expiry_time' => now()->addDays(7),
            ];
        });
    }

    /**
     * Indicate that the invitation is  expired.
     *
     * @return Factory
     */
    public function expired(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => OwnerInvitationStatus::Expired,
                'expiry_time' => now()->subDays(7),
            ];
        });
    }
}
