<?php

namespace Database\Factories;

use App\Enums\Employee\EmployeeType;
use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Invitation\RegisterInvitation;
use App\Repositories\Permission\PermissionRepository;
use App\Traits\FactoryDeletedState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class EmployeeInvitationFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model
     *
     * @var string
     */
    protected $model = EmployeeInvitation::class;

    /**
     * Configure the instance.
     *
     * @return EmployeeInvitationFactory
     */
    public function configure(): EmployeeInvitationFactory
    {
        return $this->afterCreating(function (EmployeeInvitation $invitation) {
            RegisterInvitation::create([
                'invitationable_type' => get_class($invitation),
                'invitationable_id' => $invitation->id,
                'registration_code' => $invitation->registration_code,
                'expiry_time' => $invitation->expiry_time,
            ]);
        });
    }

    /**
     * Define the model's default state
     *
     * @return array
     */
    public function definition(): array
    {
        $permissions = app(PermissionRepository::class)
            ->permissionNames();
        $permissions = collect($permissions)
            ->random(rand(1, count($permissions)))
            ->toArray();

        return [
            'invited_email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->unique()->name,
            'birth_date' => $this->faker->date,
            'phone' => $this->faker->unique()->phoneNumber,
            'role' => $this->faker->randomElement([
                EmployeeType::Administrative,
                EmployeeType::Roofer,
            ]),
            'status' => EmployeeInvitationStatus::Active,
            'contract_file' => UploadedFile::fake()->create(
                'document.pdf',
                100,
                'application/pdf'
            ),
            'expiry_time' => Carbon::now()
                ->addDays(1)
                ->format('Y-m-d H:i:s'),

            'permissions' => $permissions,
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
                'status' => EmployeeInvitationStatus::Active,
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
                'status' => EmployeeInvitationStatus::Expired,
                'expiry_time' => now(),
            ];
        });
    }

     /**
     * Indicate that the invitation is cancelled .
     *
     * @return Factory
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => EmployeeInvitationStatus::Used,
                'expiry_time' => now()->addDays(7),
            ];
        });
    }
}
