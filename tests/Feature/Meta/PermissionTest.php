<?php

namespace Tests\Feature\Meta;

use App\Models\Permission\Role;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Meta\PermissionController
 *      To the tested controller class.
 */
class PermissionTest extends TestCase
{
    /**
     * Ensure the owner permission meta existed.
     *
     * @test
     * @return void
     */
    public function test_owner(): void
    {
        $response = $this->getJson('/api/meta/permissions/owner');
        $response->assertSuccessful();
        $response->assertJsonStructure(['modules']);

        $ownerRoleId = Role::whereName(\App\Enums\Role::Owner)->first()->id;

        $content = $response->getOriginalContent();
        $modules = $content['modules'];
        foreach ($modules as $module) {
            // Assert the module attribute should exist
            $this->assertNotNull($module->module_name);
            $this->assertNotNull($module->module_description);
            $this->assertEquals($ownerRoleId, $module->role_id);

            // Assert the module permission attribute should exist
            $this->assertNotNull($module->modulePermissions);
            foreach ($module->modulePermissions as $modulePermission) {
                $this->assertNotNull($modulePermission->module_id);
                $this->assertNotNull($modulePermission->permission_id);
            }
        }
    }

    /**
     * Ensure the employee permission meta existed.
     *
     * @test
     * @return void
     */
    public function test_employee(): void
    {
        $response = $this->getJson('/api/meta/permissions/employee');
        $response->assertSuccessful();
        $response->assertJsonStructure(['modules']);

        $employeeRoleId = Role::whereName(\App\Enums\Role::Owner)->first()->id;

        $content = $response->getOriginalContent();
        $modules = $content['modules'];
        foreach ($modules as $module) {
            // Assert the module attribute should exist
            $this->assertNotNull($module->module_name);
            $this->assertNotNull($module->module_description);
            $this->assertEquals($employeeRoleId, $module->role_id);

            // Assert the module permission attribute should exist
            $this->assertNotNull($module->modulePermissions);
            foreach ($module->modulePermissions as $modulePermission) {
                $this->assertNotNull($modulePermission->module_id);
                $this->assertNotNull($modulePermission->permission_id);
            }
        }
    }
}
