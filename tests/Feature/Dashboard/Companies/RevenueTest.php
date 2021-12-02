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
        $response = $this->get($url);

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
        $response = $this->post($url, [
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
}
