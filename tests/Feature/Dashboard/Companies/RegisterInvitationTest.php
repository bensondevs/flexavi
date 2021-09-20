<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Owner;
use App\Models\Employee;

class RegisterInvitationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test invite employee.
     *
     * @return void
     */
    public function test_invite_employee()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $invitationData = [
            'invited_email' => 'test@invited.com',
            'expiry_time' => '2021-09-21',
            'title' => 'Roof cleaner master',
            'employee_type' => 2,
        ];
        $url = '/api/dashboard/companies/register_invitations/invite_employee';
        $response = $this->withHeaders($headers)->post($url, $invitationData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
            $json->has('invitation');

            $json->where('status', 'success');
        });
    }

    /**
     * Test invite owner.
     *
     * @return void
     */
    public function test_invite_owner()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $invitationData = [
            'invited_email' => 'testowner@invited.com',
            'expiry_time' => '2021-09-21',
        ];

        $url = '/api/dashboard/companies/register_invitations/invite_owner';
        $response = $this->withHeaders($headers)->post($url, $invitationData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
            $json->has('invitation');

            $json->where('status', 'success');
        });
    }
}
