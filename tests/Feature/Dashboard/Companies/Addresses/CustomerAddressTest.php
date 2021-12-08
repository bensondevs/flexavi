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
    use DatabaseTransactions;

    /**
     * A populate customer addresses feature.
     *
     * @return void
     */
    public function test_populate_customer_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->hasAddresses()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = '/api/dashboard/companies/addresses/customer?customer_id=' . $customer->id;
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
    public function test_populate_customer_trashed_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->hasAddresses()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = '/api/dashboard/companies/addresses/customer/trasheds?customer_id=' . $customer->id;
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
    public function test_store_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = '/api/dashboard/companies/addresses/customer/store';
        $response = $this->json('POST', $url, [
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
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $customer = Customer::factory()->for($company)->create();

        $address = Address::factory()->customer($customer)->create();
        $url = '/api/dashboard/companies/addresses/customer/view?address_id=' . $address->id;
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
    public function test_update_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $address = Address::factory()->customer($customer)->create();
        $url = '/api/dashboard/companies/addresses/customer/update';
        $response = $this->json('PATCH', $url, [
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
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();
            
        $address = Address::factory()->customer($customer)->create();

        $url = '/api/dashboard/companies/addresses/customer/delete';
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
    public function test_restore_customer_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();
        
        $address = Address::factory()
            ->customer($customer)
            ->softDeleted()
            ->create();

        $url = '/api/dashboard/companies/addresses/customer/restore';
        $response = $this->json('PATCH', $url, ['id' => $address->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('address');
        });
    }
}
