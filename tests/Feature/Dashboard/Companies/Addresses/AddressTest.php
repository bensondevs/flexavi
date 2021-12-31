<?php

namespace Tests\Feature\Dashboard\Companies\Addresses;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Owner, Address, Company };

class AddressTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Module test base URL
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/addresses';

    /**
     * A populate company addresses feature.
     *
     * @return void
     */
    public function test_populate_company_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('addresses');
        });
    }

    /**
     * A populate company trashed addresses feature.
     *
     * @return void
     */
    public function test_populate_company_trasheds_addresses()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/trasheds';
        $response = $this->json('GET', $url);

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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/store';
        $response = $this->json('POST', $url, [
            'address_type' => 1,

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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $address = Address::factory()->create([
            'addressable_id' => $company->id, 
            'addressable_type' => get_class($company)
        ]);
        $url = $this->baseUrl . '/view?address_id=' . $address->id;
        $response = $this->json('GET', $url);

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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/update';
        $address = Address::factory()->create([
            'addressable_id' => $company->id, 
            'addressable_type' => get_class($company)
        ]);
        $response = $this->json('PATCH', $url, [
            'id' => $address->id,

            'address_type' => 1,

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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/delete';
        $address = Address::factory()->create([
            'addressable_id' => $company->id, 
            'addressable_type' => get_class($company)
        ]);
        $response = $this->json('DELETE', $url, [
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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/restore';
        $address = Address::factory()->softDeleted()->create([
            'addressable_type' => Company::class,
            'addressable_id' => $company->id,
        ]);
        $response = $this->json('PATCH', $url, ['id' => $address->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
