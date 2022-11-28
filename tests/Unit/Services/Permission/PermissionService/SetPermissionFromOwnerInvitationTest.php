<?php

namespace Tests\Unit\Services\Permission\PermissionService;

use App\Models\Owner\OwnerInvitation;
use App\Models\Permission\Permission;
use App\Models\User\User;
use App\Services\Permission\PermissionService;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @see \App\Services\Permission\PermissionService::setPermissionFromOwnerInvitation()
 *      To the tested service method.
 * @see https://app.clickup.com/t/357ad06
 *      To view tickets when they were created
 */
class SetPermissionFromOwnerInvitationTest extends PermissionServiceTest
{
    use  WithFaker;

    /**
     * Its method running according to the needs
     *
     * @test
     * @return void
     */
    public function it_method_running_according_to_the_needs(): void
    {
        $ownerInvitation = OwnerInvitation::factory()->create();
        $user = User::factory()->owner()->create(['registration_code' => $ownerInvitation->registration_code]);
        $this->permissionService()->setPermissionFromOwnerInvitation($ownerInvitation);
        foreach ($ownerInvitation->permissions as $permission) {
            $user = $user->fresh();
            $permissionName = Permission::find($permission)->name;
            $this->assertTrue(
                $user->hasDirectPermission($permissionName)
            );
        }
    }
}
