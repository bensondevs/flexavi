<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Owner, Company };

class OwnerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test view all company owners.
     *
     * @return void
     */
    public function test_view_all_company_owners()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/owners';
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('owners');
        });
    }

    /**
     * Test non owner view all company owners.
     *
     * @return void
     */
    public function test_non_owner_view_all_company_owners()
    {
        $nonOwnerUser = User::whereDoesntHave('owner')->first();
        $token = $nonOwnerUser->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/owners';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(403);
    }

    /**
     * Test view all inviteable owners.
     *
     * @return void
     */
    public function test_view_all_invitable_owners()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->primeOnly()->inRandomOrder()->first() ?:
            Owner::factory()->prime()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/owners/inviteables';
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('owners.data');
        });
    }

    /**
     * Test store new owner.
     *
     * @return void
     */
    public function test_store_owner()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $ownerData = [
            'bank_name' => 'Added  Bank',
            'bic_code' => '911',
            'bank_account' => '9988776655',
            'bank_holder_name' => 'Added Holder', 
            'address' => 'Another street',
            'house_number' => 11,
            'house_number_suffix' => 'A',
            'zipcode' => 11178,
            'city' => 'Another City',
            'province' => 'Another Province',
        ];
        $url = '/api/dashboard/companies/owners/store';
        $response = $this->post($url, $ownerData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status', 'success');

            $json->has('owner');
            $json->has('message');
        });
    }

    /**
     * Test view owner.
     *
     * @return void
     */
    public function test_view_owner()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $viewedOwner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/owners/view?id=' . $viewedOwner->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('owner');
        });
    }

    /**
     * Test update owner.
     *
     * @return void
     */
    public function test_update_owner()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $viewedOwner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);

        $ownerData = [
            'id' => $editedOwner->id,
            'bank_name' => 'Added  Bank',
            'bic_code' => '911',
            'bank_account' => '9988776655',
            'bank_holder_name' => 'Added Holder', 
            'address' => 'Another street',
            'house_number' => 11,
            'house_number_suffix' => 'A',
            'zipcode' => 11178,
            'city' => 'Another City',
            'province' => 'Another Province',
        ];
        $url = '/api/dashboard/companies/owners/update';
        $response = $this->patch($url, $ownerData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status', 'success');

            $json->has('owner');
            $json->has('message');
        });
    }

    /**
     * Test delete owner.
     *
     * @return void
     */
    public function test_prime_owner_delete_owner()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->primeOnly()->inRandomOrder()->first() ?:
            Owner::factory()->prime()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $deletedOwner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/owners/delete';
        $response = $this->delete($url, ['id' => $deletedOwner->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status', 'success');

            $json->has('message');
        });
    }

    /**
     * Test delete prime owner.
     *
     * @return void
     */
    public function test_delete_prime_owner()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $viewedOwner = $company->owners()->primeOnly()->inRandomOrder()->first() ?:
            Owner::factory()->prime()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/owners/delete';
        $response = $this->delete($url, ['id' => $primeOwner->id]);

        $response->assertStatus(403);
    }
}