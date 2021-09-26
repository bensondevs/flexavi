<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Cost;
use App\Models\Owner;

class CostTest extends TestCase
{
    /**
     * A view all costs test.
     *
     * @return void
     */
    public function test_view_all_costs()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/costs';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('costs');
        });
    }

    /**
     * A store cost test.
     *
     * @return void
     */
    public function test_store_cost()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/costs/store';
        $response = $this->withHeaders($headers)->post($url, [
            'cost_name' => 'Store cost example',
            'amount' => 10000,
            'paid_amount' => 8000,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A update cost test.
     *
     * @return void
     */
    public function test_update_cost()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/costs/update';
        $response = $this->withHeaders($headers)->patch($url, [
            'id' => Cost::where('company_id', $owner->company_id)
                ->first()
                ->id,
            'cost_name' => 'Store cost example',
            'amount' => 10000,
            'paid_amount' => 8000,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete cost test.
     *
     * @return void
     */
    public function test_delete_cost()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/costs/delete';
        $response = $this->withHeaders($headers)->delete($url, [
            'cost_id' => Cost::where('company_id', $owner->company_id)
                ->first()
                ->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A restore cost test.
     *
     * @return void
     */
    public function test_restore_cost()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/costs/restore';

        $cost = Cost::onlyTrashed()
            ->where('company_id', $owner->company_id)
            ->first();
        if (! $cost) {
            $cost = Cost::where('company_id', $owner->company_id)->first();
            $costId = $cost->id;
            $cost->delete();

            $cost = Cost::withTrashed()->findOrFail($costId);
        }

        $response = $this->withHeaders($headers)->patch($url, [
            'cost_id' => $cost->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('cost');
        });
    }
}
