<?php

namespace Tests\Feature\Dashboard\Companies\Addresses;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\User;
use App\Models\Owner;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Address;

class CustomerAddressTest extends TestCase
{
    /**
     * A populate customer addresses feature.
     *
     * @return void
     */
    public function test_populate_customer_addresses()
    {
        $owner = Owner::inRandomOrder()->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $customer = $owner->company->customers()->first();
        $url = '/api/dashboard/companies/addresses/customer?customer_id=' . $customer->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::inRandomOrder()->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $customer = $owner->company->customers()->first();
        $url = '/api/dashboard/companies/addresses/customer/trasheds?customer_id=' . $customer->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::inRandomOrder()->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $customer = $owner->company->customers()->first();
        $url = '/api/dashboard/companies/addresses/customer/store';
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = Owner::inRandomOrder()->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $customer = $owner->company->customers()->whereHas('addresses')->first();
        $address = $customer->addresses()->first();
        $url = '/api/dashboard/companies/addresses/customer/view?address_id=' . $address->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::inRandomOrder()->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $customer = $owner->company->customers()->whereHas('addresses')->first();
        $address = $customer->addresses()->first();
        $url = '/api/dashboard/companies/addresses/customer/update';
        $response = $this->withHeaders($headers)->patch($url, [
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
        $owner = Owner::inRandomOrder()->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/addresses/customer/delete';
        $customer = $owner->company->customers()->whereHas('addresses')->first();
        $address = $customer->addresses()->first();
        $response = $this->withHeaders($headers)->delete($url, [
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
        $owner = Owner::inRandomOrder()->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/addresses/customer/restore';
        $customer = $owner->company->customers()->whereHas('addresses')->first();
        $address = $customer->addresses()->whereNotNull('deleted_at')->first();
        if (! $address) {
            $address = $customer->addresses()->first();
            $id = $address->id;
            $address->delete();
            $address = Address::onlyTrashed()->findOrFail($id);
        }
        $response = $this->withHeaders($headers)->patch($url, [
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
