<?php

namespace Tests\Feature\Dashboard\Companies\Addresses;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Owner, Customer, Company, Employee, Address };

class OwnerAddressTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * A populate owner addresses feature.
     *
     * @return void
     */
    public function test_populate_owner_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('addresses');
        });
    }

    /**
     * A populate owner addresses feature.
     *
     * @return void
     */
    public function test_populate_owner_trashed_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/trasheds';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('addresses');
        });
    }

    /**
     * A store owner address feature.
     *
     * @return void
     */
    public function test_store_owner_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/store';
        $response = $this->json('POST', $url, [
            'address_type' => 1,

            'owner_id' => $owner->id,

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
     * A view owner address feature.
     *
     * @return void
     */
    public function test_view_owner_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $address = Address::factory()->owner($owner)->create();
        $url = '/api/dashboard/companies/addresses/owner/view?address_id=' . $address->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('address');
        });
    }

    /**
     * A update owner address feature.
     *
     * @return void
     */
    public function test_update_owner_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $address = Address::factory()->owner($owner)->create();
        $url = '/api/dashboard/companies/addresses/owner/update';
        $response = $this->json('PATCH', $url, [
            'id' => $address->id,

            'address_type' => 1,

            'addressable_type' => Owner::class,
            'addressable_id' => $address->addressable->id,

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
     * A delete owner address feature.
     *
     * @return void
     */
    public function test_delete_owner_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/delete';
        $address = Address::factory()->owner($owner)->create();
        $response = $this->json('DELETE', $url, ['id' => $address->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete owner address feature.
     *
     * @return void
     */
    public function test_restore_owner_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/restore';
        $address = Address::factory()->owner($owner)->softDeleted()->create();

        $response = $this->json('PATCH', $url, ['id' => $address->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('address');
        });
    }
}