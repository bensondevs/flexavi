<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Owner;
use App\Models\Workday;

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/workdays';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/workdays/current';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $workday = Workday::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/workdays/view?id=' . $workday->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $workday = Workday::where('company_id', $owner->company_id)
            ->where('status', '<', WorkdayStatus::Processed)
            ->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $processData = [
            'workday_id' => $workday->id,
        ];
        $url = '/api/dashboard/companies/workdays/process';
        $response = $this->withHeaders($headers)->post($url, $processData);

        $response->assertStatus(200);
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $workday = Workday::where('company_id', $owner->company_id)
            ->where('status', '<', WorkdayStatus::Calculated)
            ->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $calculateData = [
            'workday_id' => $workday->id,
        ];
        $url = '/api/dashboard/companies/workdays/calculate';
        $response = $this->withHeaders($headers)->post($url, $calculateData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
