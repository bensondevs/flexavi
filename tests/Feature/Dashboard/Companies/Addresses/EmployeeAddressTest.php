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
    /**
     * A populate customer addresses feature.
     *
     * @return void
     */
    public function test_populate_employee_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = $company->employees()->first();
        $url = '/api/dashboard/companies/addresses/employee?employee_id=' . $employee->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = $company->employees()->first();
        $url = '/api/dashboard/companies/addresses/employee/trasheds?employee_id=' . $employee->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = $company->employees()->first();
        $url = '/api/dashboard/companies/addresses/employee/store';
        $response = $this->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);
        
        $employee = $company->employees()->whereHas('addresses')->first();
        $address = $employee->addresses()->first();
        $url = '/api/dashboard/companies/addresses/employee/view?address_id=' . $address->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = $company->employees()->whereHas('addresses')->first();
        $address = $employee->addresses()->first();
        $url = '/api/dashboard/companies/addresses/employee/update';
        $response = $this->patch($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/employee/delete';
        $employee = $company->employees()->whereHas('addresses')->first();
        $address = $employee->addresses()->first();
        $response = $this->delete($url, [
            'id' => $address->id,
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
    public function test_restore_employee_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/employee/restore';
        $employee = $company->employees()->whereHas('addresses')->first();
        $address = $employee->addresses()->whereNotNull('deleted_at')->first();
        if (! $address) {
            $address = $employee->addresses()->first();
            $id = $address->id;
            $address->delete();
            $address = Address::onlyTrashed()->findOrFail($id);
        }
        $response = $this->patch($url, [
            'id' => $address->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('address');
        });
    }
}
