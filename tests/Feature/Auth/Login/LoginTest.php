<?php

namespace Tests\Feature\Auth\Login;

use App\Models\Company\Company;
use App\Models\User\User;
use Database\Factories\EmployeeFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Test login as an owner
     *
     * @return void
     */
    public function test_login_as_owner(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->has('user')
                ->has('status')
                ->has('message');
        });

        $this->assertNotNull(User::find($user->id)->last_login_at);
    }

    /**
     * Test login as an employee
     *
     * @return void
     */
    public function test_login_as_employee(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->employee($company)->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertSuccessful();
        $response->assertJson(function (AssertableJson $json) {
            $json
                ->has('user')
                ->has('status')
                ->has('message');
        });

        $this->assertNotNull(User::find($user->id)->last_login_at);
    }

    /**
     * Test login as an employee
     *
     * @return void
     */
    public function test_login_as_employee_with_unverified_email(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->employee($company)->create(["email_verified_at" => null]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test login as owner with invalid password credential
     *
     * @return void
     */
    public function test_login_as_owner_with_invalid_password(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->whereType('status', 'string');
            $json->where('status', 'error');
            $json->has('message');
            $json->whereType('message', 'string');
            $json->where('message', 'Password mismatch the record!');
            $json->has('user');
        });
    }

    /**
     * Test login as employee with invalid password credential
     *
     * @return void
     */
    public function test_login_as_employee_with_invalid_password(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->employee($company)->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->whereType('status', 'string');
            $json->where('status', 'error');
            $json->has('message');
            $json->whereType('message', 'string');
            $json->where('message', 'Password mismatch the record!');
            $json->has('user');
        });
    }

    /**
     * Test login without email credential
     *
     * @return void
     */
    public function test_login_without_email(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'password' => 'password',
        ]);
        $response->assertStatus(404);
    }

    /**
     * Test login without password credential
     *
     * @return void
     */
    public function test_login_without_password(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
        ]);
        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('message', 'Password is required');
            $json->has('errors.password');
        });
    }

     /**
     * Test login as deleted owner account
     *
     * @return void
     */
    public function test_login_as_soft_deleted_owner(): void
    {
        $user = User::factory()->owner()->create() ;
        $user->owner->delete();

        $this->actingAs($user);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
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
