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

class AddressTest extends TestCase
{
    /**
     * A populate company addresses feature.
     *
     * @return void
     */
    public function test_populate_company_addresses()
    {
        $owner = Owner::inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/addresses';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('addresses');
        });
    }

    /**
     * A store company address feature.
     *
     * @return void
     */
    public function test_store_company_address()
    {
        $owner = Owner::inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/addresses/store';
        $response = $this->withHeaders($headers)->post($url, [
            'address_type' => 1,

            'company_id' => $owner->company_id,

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
     * A view company address feature.
     *
     * @return void
     */
    public function test_view_company_address()
    {
        $owner = Owner::inRandomOrder()
            ->whereHas('company.addresses')
            ->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $address = $owner->company->addresses()->first();
        $url = '/api/dashboard/companies/addresses/view?address_id=' . $address->id;
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('address');
        });
    }

    /**
     * A update company address feature.
     *
     * @return void
     */
    public function test_update_company_address()
    {
        $owner = Owner::inRandomOrder()
            ->whereHas('company.addresses')
            ->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/addresses/update';
        $address = $owner->company->addresses()->first();
        $response = $this->withHeaders($headers)->patch($url, [
            'id' => $address->id,

            'address_type' => 1,

            'company_id' => $address->addressable->id,

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
     * A delete company address feature.
     *
     * @return void
     */
    public function test_delete_company_address()
    {
        $owner = Owner::inRandomOrder()
            ->whereHas('company.addresses')
            ->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/addresses/delete';
        $address = $owner->company->addresses()->first();
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
     * A restore company address feature.
     *
     * @return void
     */
    public function test_restore_company_address()
    {
        $owner = Owner::inRandomOrder()
            ->whereHas('company.addresses')
            ->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/addresses/restore';
        $address = $owner
            ->company
            ->addresses()
            ->whereNotNull('deleted_at')
            ->first();
        if (! $address) {
            $address = $owner
                ->company
                ->addresses()
                ->first();
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
        });
    }
}
