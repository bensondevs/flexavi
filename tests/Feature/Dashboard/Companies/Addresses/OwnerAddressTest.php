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
    /**
     * A populate owner addresses feature.
     *
     * @return void
     */
    public function test_populate_owner_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->whereHas('user')->whereHas('addresses')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner';
        $response = $this->get($url);

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
        $owner = $company->owners()->whereHas('user')->whereHas('addresses')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/trasheds';
        $response = $this->get($url);

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
        $owner = $company->owners()->whereHas('user')->whereHas('addresses')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/store';
        $response = $this->post($url, [
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
        $owner = $company->owners()->whereHas('user')->whereHas('addresses')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $address = $owner->addresses()->first();
        $url = '/api/dashboard/companies/addresses/owner/view?address_id=' . $address->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->whereHas('user')->whereHas('addresses')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $address = $owner->addresses()->first();
        $url = '/api/dashboard/companies/addresses/owner/update';
        $response = $this->patch($url, [
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
        $owner = $company->owners()->whereHas('user')->whereHas('addresses')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/delete';
        $address = $owner->addresses()->first();
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
     * A delete owner address feature.
     *
     * @return void
     */
    public function test_restore_owner_address()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->whereHas('user')->whereHas('addresses')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/addresses/owner/restore';
        $address = $owner->addresses()
            ->withTrashed()
            ->whereNotNull('deleted_at')
            ->first();
        if (! $address) {
            $address = $owner->addresses()->first();
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