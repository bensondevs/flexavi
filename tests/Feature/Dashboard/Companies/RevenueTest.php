<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Company, Owner, Revenue, Work };

class RevenueTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * A load all revenues test.
     *
     * @return void
     */
    public function test_view_all_revenues()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = '/api/dashboard/companies/revenues';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Store revenue test
     * 
     * @return void
     */
    public function test_store_revenue()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $work = Work::factory()->for($company)->create();

        $url = '/api/dashboard/companies/revenues/store';
        $response = $this->json('POST', $url, [
            'work_id' => $work->id,
            'revenue_name' => 'Tip from the client',
            'amount' => 900,
            'paid_amount' => 900,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * View revenue test
     * 
     * @return void
     */
    public function test_view_revenue()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $revenue = Revenue::factory()->for($company)->create();

        $url = '/api/dashboard/companies/revenues/view?id=' . $revenue->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Update revenue test
     * 
     * @return void
     */
    public function test_update_revenue()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $revenue = Revenue::factory()->for($company)->create();

        $url = '/api/dashboard/companies/revenues/update';
        $response = $this->json('PATCH', $url, [
            'revenue_id' => $revenue->id,
            'work_id' => $work->id,
            'revenue_name' => 'Tip from the client',
            'amount' => 900,
            'paid_amount' => 900,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Delete revene test
     * 
     * @return void
     */
    public function test_delete_revenue()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $revenue = Revenue::factory()->for($company)->create();

        $url = '/api/dashboard/companies/revenues/delete';
        $response = $this->json('DELETE', $url, ['revenue_id' => $revenue]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
