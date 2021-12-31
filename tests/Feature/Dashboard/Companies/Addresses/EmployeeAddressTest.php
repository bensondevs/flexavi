<?php

namespace Tests\Feature\Dashboard\Companies\Addresses;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Owner, Customer, Company, Employee, Address };

class EmployeeAddressTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Base URL of the current test
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/addresses/employee';

    /**
     * A populate customer addresses feature.
     *
     * @return void
     */
    public function test_populate_employee_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->create();
        $url = $this->baseUrl . '?employee_id=' . $employee->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('addresses');
        });
    }

    /**
     * A populate customer trashed addresses feature.
     *
     * @return void
     */
    public function test_populate_employee_trashed_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->create();
        $url = $this->baseUrl . '/trasheds?employee_id=' . $employee->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('addresses');
        });
    }

    /**
     * A store customer address feature.
     *
     * @return void
     */
    public function test_store_employee_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->create();
        $url = $this->baseUrl . '/store';
        $response = $this->json('POST', $url, [
            'address_type' => 1,

            'employee_id' => $employee->id,

            'addressable_type' => 3,
            'other_address_type_description' => 'Home Address',

            'address' => 'Example address 123',
            'house_number' => 12,
            'house_number_suffix' => 'X',
            'zipcode' => 123510,
            'city' => 'City Example',
            'province' => 'Province Example',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A view customer address feature.
     *
     * @return void
     */
    public function test_view_employee_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);
        
        $employee = Employee::factory()->for($company)->create();
        $address = Address::factory()->employee($employee)->create();
        $url = $this->baseUrl . '/view?address_id=' . $address->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('address');
        });
    }

    /**
     * A update customer address feature.
     *
     * @return void
     */
    public function test_update_employee_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->create();
        $address = Address::factory()->employee($employee)->create();
        $url = $this->baseUrl . '/update';
        $response = $this->json('PATCH', $url, [
            'id' => $address->id,

            'address_type' => 1,

            'addressable_type' => Employee::class,
            'addressable_id' => $employee->id,

            'address' => 'Example address 123',
            'house_number' => 12,
            'house_number_suffix' => 'X',
            'zipcode' => 12345,
            'city' => 'City Example',
            'province' => 'Province Example',
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete customer address feature.
     *
     * @return void
     */
    public function test_delete_employee_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/delete';
        $employee = Employee::factory()->for($company)->create();
        $address = Address::factory()->employee($employee)->create();
        $response = $this->json('DELETE', $url, ['id' => $address->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete customer address feature.
     *
     * @return void
     */
    public function test_restore_employee_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/restore';
        $employee = Employee::factory()->for($company)->create();
        $address = Address::factory()->softDeleted()->employee($employee)->create();
        $response = $this->json('PATCH', $url, ['id' => $address->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('address');
        });
    }
}
