<?php

namespace Tests\Unit\Repositories\Employee\EmployeeInvitationRepository;

use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Invitation\RegisterInvitation;
use App\Models\Owner\Owner;
use App\Models\User\User;

/**
 * @see \App\Repositories\Employee\EmployeeInvitationRepository::handleInvitationFulfilled()
 *      To the tested repository method.
 */
class HandleInvitationFulfilledTest extends EmployeeInvitationRepositoryTest
{
    /**
     * Ensure the method creates employee with permissions.
     *
     * @test
     * @return void
     */
    public function it_creates_employee_with_permissions_when_executed(): void
    {
        $company = Company::factory()->create();
        $invitation = EmployeeInvitation::factory(state: [
            'company_id' => $company->id,
        ])->create();
        $registerInvitation = RegisterInvitation::factory(state: [
            'invitationable_type' => get_class($invitation),
            'invitationable_id' => $invitation->id,
            'registration_code' => $invitation->registration_code,
        ])->create();

        // Prepare user create
        $user = User::factory(state: [
            'registration_code' => $invitation->registration_code
        ])->withoutRole()->create();
        Owner::whereUserId($user->id)->delete();

        $repository = $this->employeeInvitationRepository(true);
        $repository->setModel($invitation);
        $repository->handleInvitationFulfilled();

        $this->assertTrue($repository->status === 'success');

        $invitation->refresh();
        $registerInvitation->refresh();

        // Assert invitation now is used
        $this->assertTrue($invitation->isUsed());

        // Get the registered user
        $registeredUser = User::whereRegistrationCode($invitation->registration_code)->first();
        $this->assertNotNull($registeredUser);

        // Assert the given role is assigned to the user
        $this->assertTrue($registeredUser->hasRole('employee'));
        $this->assertFalse($registeredUser->hasRole('owner'));

        // Assert the given permission is given to the user
        $permissions = $invitation->permissions;
        $registeredUser->hasAllPermissions($permissions);

        // Assert the employee instance is created and not doubled
        $this->assertEquals(1, Employee::whereUserId($registeredUser->id)->count());
        $employee = Employee::whereUserId($registeredUser->id)->first();
        $this->assertNotNull($employee);

        // Assert no owner instance not created
        $this->assertTrue(Owner::whereUserId($registeredUser->id)->doesntExist());
    }
}
