<?php

namespace Tests\Integration\Auth\Login;

use App\Enums\Employee\EmploymentStatus;
use App\Models\Company\Company;
use App\Models\User\User;
use Database\Factories\EmployeeFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Auths\LoginController;
 *      to the tested controller
 */
class LoginAsEmployeeTest extends TestCase
{
    use WithFaker;

    /**
     * Test login as deleted employee account
     *
     * @return void
     */
    public function test_login_as_soft_deleted_employee(): void
    {
        $employee = EmployeeFactory::new()->create();
        tryIsset(fn () => $employee->user->owner->forceDelete());
        $employee->delete();

        $this->actingAs($employee->user);

        $response = $this->postJson('/api/auth/login', [
            'email' => $employee->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->where('user', null)
                ->where('status', 'error')
                ->has('message');
        });
    }
}
