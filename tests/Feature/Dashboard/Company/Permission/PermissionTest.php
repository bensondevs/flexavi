<?php

namespace Tests\Feature\Dashboard\Company\Permission;

use App\Models\Employee\Employee;
use App\Models\Permission\Permission;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use  WithFaker;

    /**
     * test populate userable permisssions
     *
     * @return void
     */
    public function test_populate_userable_permissions()
    {
        $user = User::factory()
            ->owner()->create();
        $this->actingAs($user);

        $employee = Employee::factory()->create();
        $employee->user->givePermissionTo(
            Permission::inRandomOrder()->limit(rand(20, 30))->get()
        );

        $response = $this->getJson("/api/dashboard/companies/permissions/of_userable?employee_id=$employee->id");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "permissions" => [
                [
                    "name",
                    "active"
                ],
            ]
        ]);
    }
}
