<?php

namespace Database\Factories;

use App\Models\Employee\EmployeeInvitation;
use App\Models\Invitation\RegisterInvitation;
use App\Models\Owner\OwnerInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegisterInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RegisterInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'registration_code' => randomToken(),
            'expiry_time' => carbon()
                ->now()
                ->addDays(3),
        ];
    }

    /**
     * Indicate that the model is for owner.
     *
     * @return Factory
     */
    public function owner(): Factory
    {
        $ownerInvitation = OwnerInvitation::factory()->create();
        return $this->state(function (array $attributes) use ($ownerInvitation) {
            return [
                'invitationable_type' => get_class($ownerInvitation),
                'invitationable_id' => $ownerInvitation->id,
                'registration_code' => $ownerInvitation->registration_code,
            ];
        });
    }

    /**
     * Indicate that the model is for employee.
     *
     * @return Factory
     */
    public function employee(): Factory
    {
        $employeeInvitation = EmployeeInvitation::factory()->create();
        return $this->state(function (array $attributes) use ($employeeInvitation) {
            return [
                'invitationable_type' => get_class($employeeInvitation),
                'invitationable_id' => $employeeInvitation->id,
                'registration_code' => $employeeInvitation->registration_code,
            ];
        });
    }
}
