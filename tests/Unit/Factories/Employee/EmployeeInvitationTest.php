<?php

namespace Tests\Unit\Factories\Employee;

use App\Enums\Employee\EmployeeType;
use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Employee\EmployeeInvitation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeInvitationTest extends TestCase
{
    use WithFaker;

    /**
     * Ensure the permissions column is shown as array in the attribute.
     * 
     * @return void
     */
    public function test_permissions_column_is_json_array(): void
    {
        $invitation = EmployeeInvitation::factory()
            ->create()
            ->refresh();
        $permissions = $invitation->permissions;
        
        $this->assertIsArray($permissions);
    }

    /**
     * Test create a company employee invitation instance
     *
     * @return void
     */
    public function test_create_company_employee_invitation_instance(): void
    {
        // make an instance
        $invitation = EmployeeInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('employee_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'birth_date' => $invitation->birth_date,
            'role' => $invitation->role,
            'status' => $invitation->status,
            'contract_file_path' => $invitation->contract_file_path,
            'expiry_time' => $invitation->expiry_time,
        ]);
    }

    /**
     * Test create multiple company employee invitation instances
     *
     * @return void
     */
    public function test_create_multiple_company_employee_invitation_instances(): void
    {
        // make the instances
        $count = 10;
        $invitations = EmployeeInvitation::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($invitations) === $count);
    }

    /**
     * Test update a company employee invitation instance
     *
     * @return void
     */
    public function test_update_company_employee_invitation_instance(): void
    {
        // make an instance
        $invitation = EmployeeInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('employee_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'birth_date' => $invitation->birth_date,
            'role' => $invitation->role,
            'status' => $invitation->status,
            'contract_file_path' => $invitation->contract_file_path,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // generate dummy data
        $invitedEmail = $this->faker->safeEmail;
        $name = $this->faker->name;
        $birthDate = $this->faker->date;
        $phone = $this->faker->phoneNumber;
        $expiryTime = Carbon::now()
            ->addDays(1)
            ->format('Y-m-d H:i:s');
        $role = $this->faker->randomElement([
            EmployeeType::Administrative,
            EmployeeType::Roofer,
        ]);
        $status = $this->faker->randomElement([
            EmployeeInvitationStatus::Active,
            EmployeeInvitationStatus::Used,
            EmployeeInvitationStatus::Expired,
        ]);

        // update instance
        $invitation->update([
            'invited_email' => $invitedEmail,
            'name' => $name,
            'birth_date' => $birthDate,
            'phone' => $phone,
            'expiry_time' => $expiryTime,
            'role' => $role,
            'status' => $status,
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('employee_invitations', [
            'id' => $invitation->id,
            'registration_code' => $invitation->registration_code,
            'invited_email' => $invitation->invited_email,
            'name' => $name,
            'birth_date' => $birthDate,
            'phone' => $phone,
            'expiry_time' => $expiryTime,
            'role' => $role,
            'status' => $status,
            'contract_file_path' => $invitation->contract_file_path,
        ]);
    }

    /**
     * Test soft delete a company employee invitation instance
     *
     * @return void
     */
    public function test_soft_delete_company_employee_invitation_instance(): void
    {
        // make an instance
        $invitation = EmployeeInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('employee_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'birth_date' => $invitation->birth_date,
            'role' => $invitation->role,
            'status' => $invitation->status,
            'contract_file_path' => $invitation->contract_file_path,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // soft delete the instance
        $invitation->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($invitation);
    }

    /**
     * Test hard delete a company employee invitation instance
     *
     * @return void
     */
    public function test_hard_delete_company_employee_invitation_instance(): void
    {
        // make an instance
        $invitation = EmployeeInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('employee_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'birth_date' => $invitation->birth_date,
            'role' => $invitation->role,
            'status' => $invitation->status,
            'contract_file_path' => $invitation->contract_file_path,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // hard delete the instance
        $invitationId = $invitation->id;
        $invitation->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($invitation);
        $this->assertDatabaseMissing('employee_invitations', [
            'id' => $invitationId,
        ]);
    }

    /**
     * Test restore a trashed company employee invitation instance
     *
     * @return void
     */
    public function test_restore_trashed_company_employee_invitation_instance(): void
    {
        // make an instance
        $invitation = EmployeeInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('employee_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'birth_date' => $invitation->birth_date,
            'role' => $invitation->role,
            'status' => $invitation->status,
            'contract_file_path' => $invitation->contract_file_path,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // soft delete the instance
        $invitation->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($invitation);

        // restore the trashed instance
        $invitation->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($invitation);
    }
}
