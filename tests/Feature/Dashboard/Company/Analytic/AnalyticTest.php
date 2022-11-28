<?php

namespace Tests\Feature\Dashboard\Company\Analytic;

use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AnalyticTest extends TestCase
{
    use WithFaker;

    /**
     * test get result trends
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
public function test_result_trends()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $groupBy = "daily";
        $response = $this->getJson("/api/dashboard/companies/analytics/result_trends?group_by={$groupBy}");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has("results");
        });
    }
     */


    /**
     * test get revenue trends
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_revenue_trends()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $groupBy = "daily";
        $response = $this->getJson("/api/dashboard/companies/analytics/revenue_trends?group_by={$groupBy}");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has("revenues");
        });
    }
     */

    /**
     * test get cost trends
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
  public function test_cost_trends()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $groupBy = "daily";
        $response = $this->getJson("/api/dashboard/companies/analytics/cost_trends?group_by={$groupBy}");

        // $response->dump();

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has("costs");
        });
    }
     */


    /**
     * test get profit trends
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
public function test_profit_trends()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $groupBy = "daily";
        $response = $this->getJson("/api/dashboard/companies/analytics/profit_trends?group_by={$groupBy}");

        // $response->dump();

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has("profits");
        });
    }
     */


    /**
     * test get warranties per roofer
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
 public function test_warranties_per_roofer()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $response = $this->getJson('/api/dashboard/companies/analytics/warranties_per_roofer');

        // $response->dump();

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has("warranties_per_roofer");
        });
    }
     */


    /**
     * test get customer shortages
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
public function test_customer_shortages()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $response = $this->getJson('/api/dashboard/companies/analytics/customer_shortages');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has("customer_shortages");
        });
    }
     */


    /**
     * Test populate company analytic yesterday_cost_revenue
     *
     * @return void
     */
    public function test_polpulate_analytic_yesterday_cost_revenue()
    {
        $user = User::factory()
            ->owner()->create();
        $this->actingAs($user);
        $response = $this->getJson('/api/dashboard/companies/analytics/yesterday_cost_revenue');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "cost" => [
                "today_sum",
                "yesterday_sum",
            ],
            "revenue" => [
                "today_sum",
                "yesterday_sum",
            ],
        ]);
    }

    /**
     * Test populate company analytic summaries
     *
     * @return void
     */
    public function test_polpulate_analytic_summaries()
    {
        $user = User::factory()
            ->owner()->create();
        $this->actingAs($user);
        $response = $this->getJson('/api/dashboard/companies/analytics/summaries');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "summaries" => [
                //
            ]
        ]);
    }

    /**
     * Test populate analytic roofer profit
     *
     * @return void
     *
     * @todo Hidden feature for next release
     *  TODO: Hidden feature for next release
     *
 public function test_populate_analytic_roofer_profit()
    {
        $user = User::factory()->owner()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/dashboard/companies/analytics/roofer_profit');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "roofer_profit" => [
                //
            ]
        ]);
    }
     */


    /**
     * Test populate analytic best selling services
     *
     * @return void
     */
    public function test_populate_analytic_best_selling_services()
    {
        $user = User::factory()->owner()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/dashboard/companies/analytics/best_selling_services');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "services" => [
                //
            ]
        ]);
    }

    /**
     * Test populate analytic best selling services per roofer
     *
     * @return void
     *
     * @todo Hidden feature for next release
     *  TODO: Hidden feature for next release
     *
 public function test_populate_analytic_best_selling_services_per_roofer()
    {
        $user = User::factory()->owner()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/dashboard/companies/analytics/best_selling_services_per_poofer');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "services" => [
                //
            ]
        ]);
    }

     */

    /**
     * Test populate analytic best roofers per province
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
 public function test_populate_analytic_best_roofers_per_province()
    {
        $user = User::factory()->owner()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/dashboard/companies/analytics/best_roofers_per_province');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "roofers" => [
                //
            ]
        ]);
    }
     */


    /**
     * Test polpulate company analytic result graph
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
 public function test_polpulate_analytic_result_graph()
    {
        $user = User::factory()
            ->owner()->create();
        $this->actingAs($user);
        $responseYearly = $this->getJson(
            '/api/dashboard/companies/analytics/result_graph?type=yearly'
        );
        $responseYearly->assertStatus(200);
        $responseYearly->assertJson(function (AssertableJson $json) {
            $json->has('revenues');
            $json->has('reveues.year');
            $json->has('reveues.amount');
            $json->has('costs');
            $json->has('costs.year');
            $json->has('costs.amount');
        });

        $responseMonthly = $this->getJson(
            '/api/dashboard/companies/analytics/result_graph?type=monthly'
        );
        $responseMonthly->assertStatus(200);
        $responseMonthly->assertJson(function (AssertableJson $json) {
            $json->has('revenues');
            $json->has('reveues.month');
            $json->has('reveues.year');
            $json->has('reveues.amount');
            $json->has('costs');
            $json->has('costs.month');
            $json->has('costs.year');
            $json->has('costs.amount');
        });

        $responseWeekly = $this->getJson(
            '/api/dashboard/companies/analytics/result_graph?type=weekly'
        );
        $responseWeekly->assertStatus(200);
        $responseWeekly->assertJson(function (AssertableJson $json) {
            $json->has('revenues');
            $json->has('reveues.week');
            $json->has('reveues.month');
            $json->has('reveues.year');
            $json->has('reveues.amount');
            $json->has('costs');
            $json->has('costs.week');
            $json->has('costs.month');
            $json->has('costs.year');
            $json->has('costs.amount');
        });
    }
     */
}
