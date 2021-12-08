<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Owner, Workday, Company };

use App\Enums\Workday\WorkdayStatus;

class WorkdayTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tested module base url
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/workdays';

    /**
     * A populate workday test.
     *
     * @return void
     */
    public function test_view_all_workdays()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('workdays')
                ->has('workdays.data')
                ->has('workdays.current_page');
        });
    }

    /**
     * A view current workday test.
     *
     * @return void
     */
    public function test_view_current_workday()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/current';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('workday');
        });
    }

    /**
     * A view workday test.
     *
     * @return void
     */
    public function test_view_workday()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = Workday::factory()
            ->for($company)
            ->create();

        $url = $this->baseUrl . '/view?id=' . $workday->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('workday');
        });
    }

    /**
     * Process workday test.
     *
     * @return void
     */
    public function test_process_workday()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = Workday::factory()
            ->for($company)
            ->prepared()
            ->create();
        $url = $this->baseUrl . '/process';
        $response = $this->post($url, ['workday_id' => $workday->id]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Calculate workday test.
     *
     * @return void
     */
    public function test_calculate_workday()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = Workday::factory()
            ->for($company)
            ->processed()
            ->create();

        $url = '/api/dashboard/companies/workdays/calculate';
        $response = $this->post($url, ['workday_id' => $workday->id]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
