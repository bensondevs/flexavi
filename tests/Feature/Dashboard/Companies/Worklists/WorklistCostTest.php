<?php

namespace Tests\Feature\Dashboard\Companies\Worklists;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Company, Owner, Workday, Worklist };

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()
            ->inRandomOrder()
            ->first() ?: Worklist::factory()->create(['company_id' => $company->id]);
        $url = $this->baseUrl . '?worklist_id=' . $worklist->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()
            ->inRandomOrder()
            ->first() ?: Worklist::factory()->create(['company_id' => $company->id]);
        $url = $this->baseUrl . '/store_record';
        $response = $this->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()
            ->inRandomOrder()
            ->first() ?: Worklist::factory()->create(['company_id' => $company->id]);
        $cost = $company->costs()
            ->inRandomOrder()
            ->first() ?: Cost::factory()->create(['company_id' => $company->id]);
        $url = $this->baseUrl . '/record';
        $response = $this->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $cost = $company->costs()
            ->inRandomOrder()
            ->whereHas('costables', function ($costable) {
                $costable->where('costable_type', Worklist::class);
            })->first();
        $costable = $cost->costables()
            ->where('costable_type', Worklist::class)
            ->first();
        $worklist = $costable->costable;
        $url = $this->baseUrl . '/unrecord';
        $response = $this->post($url, [
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
     * A record many costs to worklist.
     *
     * @return void
     */
    public function test_record_many_costs_to_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $costIds = [];
        foreach ($company->costs()->take(10)->get() as $cost) {
            array_push($costIds, $cost->id);
        }
        $worklist = $company->worklists()->inRandomOrder()->first();
        $url = $this->baseUrl . '/record_many';
        $response = $this->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        do {
            $worklist = $company->worklists()
                ->inRandomOrder()
                ->first();
        } while ($worklist->costs()->count() < 2);

        $unrecordedCostIds = [];
        foreach ($worklist->costs as $index => $cost) {
            if ($index && rand(0, 1)) {
                break;
            }

            array_push($unrecordedCostIds, $cost->id);
        }

        $url = $this->baseUrl . '/unrecord_many';
        $response = $this->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        do {
            $worklist = $company->worklists()
                ->inRandomOrder()
                ->first();
        } while ($worklist->costs()->count() < 2);

        $url = $this->baseUrl . '/truncate';
        $response = $this->post($url, [
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
