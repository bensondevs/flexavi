<?php

namespace Tests\Feature\Dashboard\Companies\Addresses;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Owner, Customer, Company, Employee, Address };

class CustomerAddressTest extends TestCase
{
    /**
     * A populate customer addresses feature.
     *
     * @return void
     */
    public function test_populate_customer_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/addresses/customer?customer_id=' . $customer->id;
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
    public function test_populate_customer_trashed_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/addresses/customer/trasheds?customer_id=' . $customer->id;
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
    public function test_store_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/addresses/customer/store';
        $response = $this->post($url, [
            'address_type' => 1,

            'customer_id' => $customer->id,

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
    public function test_view_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $address = $customer->addresses()->first() ?:
            Address::factory()->create(['addressable_type' => Customer::class, 'addressable_id' => $customer->id]);
        $url = '/api/dashboard/companies/addresses/customer/view?address_id=' . $address->id;
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
    public function test_update_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $address = $customer->addresses()->first() ?:
            Address::factory()->create(['addressable_type' => Customer::class, 'addressable_id' => $customer->id]);
        $url = '/api/dashboard/companies/addresses/customer/update';
        $response = $this->patch($url, [
            'id' => $address->id,

            'address_type' => 1,

            'addressable_type' => Customer::class,
            'addressable_id' => $customer->id,

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
    public function test_delete_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);
            
        $address = $customer->addresses()->first() ?:
            Address::factory()->create(['addressable_type' => Customer::class, 'addressable_id' => $customer->id]);

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
    public function test_restore_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);
            
        $address = $customer->addresses()->onlyTrashed()->first() ?:
            Address::factory()->softDeleted()->create(['addressable_type' => Customer::class, 'addressable_id' => $customer->id]);

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
