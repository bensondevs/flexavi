<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Owner;
use App\Models\Workday;
use App\Models\Worklist;

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/worklists';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $workday = Workday::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/worklists/of_workday?workday_id=' . $workday->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/worklists/trasheds';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $workday = Workday::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklistData = [
            'workday_id' => $workday->id,
            'worklist_name' => 'Worklist Name Example',
        ];
        $url = '/api/dashboard/companies/worklists/store';
        $response = $this->withHeaders($headers)->post($url, $worklistData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A update worklist test.
     *
     * @return void
     */
    public function test_update_worklist()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $worklist = Worklist::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklistData = [
            'id' => $worklist->id,
            'worklist_name' => 'Worklist Name Example',
        ];
        $url = '/api/dashboard/companies/worklists/update';
        $response = $this->withHeaders($headers)->patch($url, $worklistData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $worklist = Worklist::where('company_id', $owner->company_id)
            ->where('status', WorklistStatus::Prepared)
            ->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/process';
        $response = $this->withHeaders($headers)->post($url, $worklistData);

        $response->assertStatus(200);
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $worklist = Worklist::where('company_id', $owner->company_id)
            ->where('status', WorklistStatus::Processed)
            ->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/calculate';
        $response = $this->withHeaders($headers)->post($url, $worklistData);

        $response->assertStatus(200);
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $worklist = Worklist::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/delete';
        $response = $this->withHeaders($headers)->delete($url, $worklistData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $worklist = Worklist::withTrashed()
            ->where('company_id', $owner->company_id)
            ->whereNotNull('deleted_at')
            ->first();

        if (! $worklist) {
            $worklist = Worklist::where('company_id', $owner->company_id)->first();
            $deletedWorklistId = $worklist->id;
            $worklist->delete();

            $worklist = Worklist::withTrashed()->findOrFail($deletedWorklistId);
        }

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklistData = ['id' => $worklist->id];
        $url = '/api/dashboard/companies/worklists/restore';
        $response = $this->withHeaders($headers)->patch($url, $worklistData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
