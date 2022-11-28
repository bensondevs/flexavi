<?php

namespace Tests\Feature\Dashboard\Company\Owner;

use App\Models\Owner\Owner;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Owner\OwnerPermissionController
 *      To the tested controller class.
 */
class OwnerPermissionTest extends TestCase
{
    /**
     * Authenticate current user as owner.
     *
     * @return User
     */
    private function authenticateAsOwner(): User
    {
        $user = User::factory()->owner()->create();
        $owner = $user->load(['owner'])->owner;

        // Set owner as main owner
        $owner->is_prime_owner = true;
        $owner->save();

        // Assign permissions
        $user->syncPermissions([
            'edit owners',
            'view owners',
        ]);

        // Acting as owner user
        $this->actingAs($user);

        return $user;
    }

    /**
     * Test populate employee permissions works.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Owner\OwnerPermissionController::ownerPermissions()
     *      To the tested controller method.
     */
    public function test_populate_owner_permissions(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Prepare employee as the subject of test
        $owner = Owner::factory()->create([
            'company_id' => $company->id,
            'is_prime_owner' => false,
        ])->fresh();
        $owner->user->syncPermissions(
            $assignedPermissions = collect(app(PermissionRepository::class)
                ->permissionNames())->take(rand(3, 5))
        );

        // Make request to the controller method endpoint URL
        $url = '/api/dashboard/companies/owners/permissions/?owner_id=' . $owner->id;
        $response = $this->getJson($url);

        // Assert the response is success as expected
        $response->assertSuccessful();
        $content = $response->getOriginalContent();
        $permissions = collect($content['permissions']);
        foreach ($assignedPermissions as $assignedPermission) {
            $found = $permissions->where('name', $assignedPermission)->first();
            $found ?
                $this->assertTrue($found['active']) :
                $this->assertFalse($found['active']);
        }
    }

    /**
     * Test update employee permissions.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Owner\OwnerPermissionController::update()
     *      To the tested controller method.
     */
    public function test_update_owner_permissions(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Prepare employee as the subject of test
        $owner = Owner::factory()->create([
            'company_id' => $company->id,
            'is_prime_owner' => false,
        ])->fresh();

        // Prepare new permissions to be attached to employee
        $newPermissions = collect(
            app(PermissionRepository::class)->permissionNames()
        )->take(rand(3, 5))->toArray();

        // Prepare URL for the testing
        $url = '/api/dashboard/companies/owners/permissions/update/';

        /**
         * Update with single string input
         */
        // Make request to the controller method endpoint URL
        $response = $this->postJson($url, [
            'owner_id' => $owner->id,
            'permission_names' => $newPermissions[0],
        ]);
        $response->assertSuccessful();

        // Assert the employee does have the permission
        $ownerUser = $owner->user;
        foreach ($newPermissions as $newPermission) {
            $ownerUser->hasPermissionTo($newPermission);
        }

        /**
         * Update with array instance
         */
        // Make request to the controller method endpoint URL
        $response = $this->postJson($url, [
            'owner_id' => $owner->id,
            'permission_names' => $newPermissions,
        ]);

        // Assert the response is success as expected
        $response->assertSuccessful();

        // Assert the employee does have the permission
        foreach ($newPermissions as $newPermission) {
            $ownerUser->hasPermissionTo($newPermission);
        }

        /**
         * Update with JSON array instance.
         */
        // Make request to the controller method endpoint URL
        $response = $this->postJson($url, [
            'owner_id' => $owner->id,
            'permission_names' => json_encode($newPermissions),
        ]);

        // Assert the response is success as expected
        $response->assertSuccessful();

        // Assert the employee does have the permission
        foreach ($newPermissions as $newPermission) {
            $ownerUser->hasPermissionTo($newPermission);
        }
    }
}
