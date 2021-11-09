<?php

namespace Tests\Feature\Dashboard\Companies\Worklists;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    Company, 
    Owner, 
    Workday, 
    Worklist, 
    Cost, 
    Costable 
};

class WorklistCostTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = '/api/dashboard/companies/worklists/costs';

    /**
     * A view all worklist costs test.
     *
     * @return void
     */
    public function test_view_all_worklist_costs()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '?worklist_id=' . $worklist->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('costs');
        });
    }

    /**
     * A store cost and record to worklist.
     *
     * @return void
     */
    public function test_store_cost_and_record_to_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()->for($company)->create();
        $url = $this->baseUrl . '/store_record';
        $response = $this->json('POST', $url, [
            'cost_name' => 'Cost Name Example',
            'amount' => 9000,
            'paid_amount' => 1000,
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status.0', 'success');
            $json->where('status.1', 'success');

            $json->has('message');
        });
    }

    /**
     * A record cost to worklist.
     *
     * @return void
     */
    public function test_record_cost_to_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()->for($company)->create();
        $cost = $company->costs()
            ->inRandomOrder()
            ->first() ?: Cost::factory()->for($company)->create(['company_id' => $company->id]);
        $url = $this->baseUrl . '/record';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'cost_id' => $cost->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * An unrecord cost from worklist.
     *
     * @return void
     */
    public function test_unrecord_cost_from_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()
            ->for($company)
            ->create();
        $costable = Costable::factory()
            ->for($company)
            ->worklist($worklist)
            ->create();
        
        $url = $this->baseUrl . '/unrecord';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'cost_id' => $costable->cost_id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A record many costs to worklist.
     *
     * @return void
     */
    public function test_record_many_costs_to_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $costs = Cost::factory()->for($company)->count(10)->create();

        $costIds = [];
        foreach ($costs as $cost) {
            array_push($costIds, $cost->id);
        }
        $worklist = Worklist::factory()->for($company)->create();
        $url = $this->baseUrl . '/record_many';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'cost_ids' => $costIds,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A unrecord many costs to worklist.
     *
     * @return void
     */
    public function test_unrecord_many_costs_from_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()
            ->for($company)
            ->create();
        $costables = Costable::factory()
            ->worklist($worklist)
            ->count(5)
            ->create();

        $unrecordedCostIds = [];
        foreach ($worklist->costs as $index => $cost) {
            if ($index && rand(0, 1)) {
                break;
            }

            array_push($unrecordedCostIds, $cost->id);
        }

        $url = $this->baseUrl . '/unrecord_many';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'cost_ids' => $unrecordedCostIds,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A truncate costs to worklist.
     *
     * @return void
     */
    public function test_truncate_worklist_costs()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()
            ->for($company)
            ->create();

        $url = $this->baseUrl . '/truncate';
        $response = $this->json('POST', $url, ['worklist_id' => $worklist->id]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
