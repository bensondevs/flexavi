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
     * A populate workday test.
     *
     * @return void
     */
    public function test_view_all_workdays()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->whereHas('user')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/workdays';
        $response = $this->get($url);

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
        $owner = $company->owners()->whereHas('user')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/workdays/current';
        $response = $this->get($url);

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
        $owner = $company->owners()->whereHas('user')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = $company->workdays()->inRandomOrder()->first() ?:
            Workday::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/workdays/view?id=' . $workday->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->whereHas('user')->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = $company->workdays()
            ->where('status', '<', WorkdayStatus::Processed)
            ->inRandomOrder()
            ->first();
        if (! $workday) {
            $workday = Workday::factory()->create([
                'company_id' => $company->id,
                'status' => WorkdayStatus::Prepared,
            ]);
        }

        $processData = [
            'workday_id' => $workday->id,
        ];
        $url = '/api/dashboard/companies/workdays/process';
        $response = $this->post($url, $processData);

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
        $owner = Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = $company->workdays()
            ->where('status', '<', WorkdayStatus::Calculated)
            ->inRandomOrder()
            ->first();
        if (! $workday) {
            $workday = Workday::factory()->create([
                'company_id' => $company->id,
                'status' => WorkdayStatus::Processed,
            ]);
        }

        $calculateData = [
            'workday_id' => $workday->id,
        ];
        $url = '/api/dashboard/companies/workdays/calculate';
        $response = $this->post($url, $calculateData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
