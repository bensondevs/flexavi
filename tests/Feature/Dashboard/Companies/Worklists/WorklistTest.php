<?php

namespace Tests\Feature\Dashboard\Companies\Worklists;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Owner, Workday, Worklist, Company };

use App\Enums\Worklist\WorklistStatus;

class WorklistTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A view all worklists test.
     *
     * @return void
     */
    public function test_view_all_worklists()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/worklists';
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists')
                ->has('worklists.data')
                ->has('worklists.current_page');
        });
    }

    /**
     * A view all worklists test.
     *
     * @return void
     */
    public function test_view_workday_worklists()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create([
                'company_id' => $company->id,
                'user_id' => User::factory()->create()->id,
            ]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = $company->workdays()->inRandomOrder()->first() ?:
            Workday::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/worklists/of_workday?workday_id=' . $workday->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists')
                ->has('worklists.data')
                ->has('worklists.current_page');
        });
    }

    /**
     * A view all worklists test.
     *
     * @return void
     */
    public function test_view_trashed_worklists()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/worklists/trasheds';
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists')
                ->has('worklists.data')
                ->has('worklists.current_page');
        });
    }

    /**
     * A store worklist test.
     *
     * @return void
     */
    public function test_store_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $workday = $company->workdays()->inRandomOrder()->first() ?:
            Workday::factory()->create(['company_id' => $company->id]);

        $worklistData = [
            'workday_id' => $workday->id,
            'worklist_name' => 'Worklist Name Example',
        ];
        $url = '/api/dashboard/companies/worklists/store';
        $response = $this->post($url, $worklistData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A view worklist test.
     *
     * @return void
     */
    public function test_view_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()->inRandomOrder()->first() ?:
            Worklist::factory()->create(['company_id' => $company->id]);

        $url = '/api/dashboard/companies/worklists/view?id=' . $worklist->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklist');
        });
    }

    /**
     * A update worklist test.
     *
     * @return void
     */
    public function test_update_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()->inRandomOrder()->first() ?:
            Worklist::factory()->create(['company_id' => $company->id]);

        $worklistData = [
            'id' => $worklist->id,
            'worklist_name' => 'Worklist Name Example',
        ];
        $url = '/api/dashboard/companies/worklists/update';
        $response = $this->patch($url, $worklistData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A process worklist test.
     *
     * @return void
     */
    public function test_process_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->primeOnly()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()->prepared()->inRandomOrder()->first() ?:
            Worklist::factory()->prepared()->create(['company_id' => $company->id]);

        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/process';
        $response = $this->post($url, $worklistData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A calculate worklist test.
     *
     * @return void
     */
    public function test_calculate_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->primeOnly()->inRandomOrder()->first() ?:
            Owner::factory()->prime()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()->processed()->inRandomOrder()->first() ?:
            Worklist::factory()->processed()->create(['company_id' => $company->id]);

        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/calculate';
        $response = $this->post($url, $worklistData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete worklist test.
     *
     * @return void
     */
    public function test_delete_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->primeOnly()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()->inRandomOrder()->first() ?:
            Worklist::factory()->create(['company_id' => $company->id]);

        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/delete';
        $response = $this->delete($url, $worklistData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A restore worklist test.
     *
     * @return void
     */
    public function test_restore_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->primeOnly()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = $company->worklists()->onlyTrashed()->inRandomOrder()->first() ?:
            Worklist::factory()->softDeleted()->create(['company_id' => $company->id]);

        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/restore';
        $response = $this->patch($url, $worklistData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
