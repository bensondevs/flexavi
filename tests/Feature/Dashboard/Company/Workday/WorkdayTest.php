<?php

namespace Tests\Feature\Dashboard\Company\Workday;

use App\Models\{User\User, Workday\Workday, Worklist\Worklist, Worklist\WorklistEmployee};
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WorkdayTest extends TestCase
{
    /**
     * Test populate company workdays
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
     public function test_populate_company_workdays()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson('/api/dashboard/companies/workdays');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('workdays');
            $json->whereType('workdays.data', 'array');

            // pagination meta
            $json->has('workdays.current_page');
            $json->has('workdays.first_page_url');
            $json->has('workdays.from');
            $json->has('workdays.last_page');
            $json->has('workdays.last_page_url');
            $json->has('workdays.links');
            $json->has('workdays.next_page_url');
            $json->has('workdays.path');
            $json->has('workdays.per_page');
            $json->has('workdays.prev_page_url');
            $json->has('workdays.to');
            $json->has('workdays.total');
        });
    }
     */


    /**
     * Test get current company workday
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_get_current_company_workday()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        Workday::factory()
            ->for($user->owner->company)
            ->date(now()->format('Y-m-d'))
            ->create();

        $response = $this->getJson('/api/dashboard/companies/workdays/current');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('workday');

            $json->has('workday.id');
        });
    }
    */


    /**
     * Test get company workday by id
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_get_company_workday_by_id()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $workday = Workday::factory()
            ->for($user->owner->company)
            ->create();
        $worklists = Worklist::factory()->count(rand(2, 5))
            ->for($workday)
            ->create()
            ->each(function ($worklist) {
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $user = User::factory()->employee()->create();
                    WorklistEmployee::create([
                        'worklist_id' => $worklist->id, 'user_id' => $user->id
                    ]);
                }
            });

        $response = $this->getJson('/api/dashboard/companies/workdays/view?date=' . $workday->date);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('workday');

            $json->has('workday.id');
        });
    }
    */

    /**
     * Test get company workday by date
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
     public function test_get_company_workday_by_date()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $workday = Workday::factory()
            ->for($user->owner->company)
            ->create();


        $response = $this->getJson('/api/dashboard/companies/workdays/view?date=' . $workday->date);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('workday');

            $json->has('workday.id');
        });
    }
     */


    /**
     * Test process company workday
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
     public function test_process_company_workday()
     {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $workday = Workday::factory()
            ->for($user->owner->company)
            ->prepared()
            ->create();


        $response = $this->postJson('/api/dashboard/companies/workdays/process', [
            'id' => $workday->id
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully process workday.');
        });
    }
    */


    /**
     * Test calculate company workday
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_calculate_company_workday()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $workday = Workday::factory()
            ->for($user->owner->company)
            ->processed()
            ->create();


        $response = $this->postJson('/api/dashboard/companies/workdays/calculate', [
            'id' => $workday->id
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully calculate workday.');
        });
    }
     */
}
