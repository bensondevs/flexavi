<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Cost, Owner, Company };

class CostTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A view all costs test.
     *
     * @return void
     */
    public function test_view_all_costs()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/costs';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('costs');
        });
    }

    /**
     * A store cost test.
     *
     * @return void
     */
    public function test_store_cost()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/costs/store';
        $response = $this->json('POST', $url, [
            'cost_name' => 'Store cost example',
            'amount' => 10000,
            'paid_amount' => 8000,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A update cost test.
     *
     * @return void
     */
    public function test_update_cost()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $cost = Cost::factory()->for($company)->create();

        $url = '/api/dashboard/companies/costs/update';
        $response = $this->json('PATCH', $url, [
            'id' => $cost->id,
            'cost_name' => 'Store cost example',
            'amount' => 10000,
            'paid_amount' => 8000,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete cost test.
     *
     * @return void
     */
    public function test_delete_cost()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $cost = Cost::factory()->for($company)->create();

        $url = '/api/dashboard/companies/costs/delete';
        $response = $this->json('DELETE', $url, ['cost_id' => $cost->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A restore cost test.
     *
     * @return void
     */
    public function test_restore_cost()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $cost = Cost::factory()->for($company)->softDeleted()->create();

        $url = '/api/dashboard/companies/costs/restore';
        $response = $this->json('PATCH', $url, ['cost_id' => $cost->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('cost');
        });
    }
}
