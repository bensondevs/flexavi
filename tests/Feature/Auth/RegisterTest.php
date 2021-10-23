<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Models\RegisterInvitation;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test success register
     *
     * @return void
     */
    public function test_success_register()
    {
        $invitation = RegisterInvitation::where('status', RegisterInvitationStatus::Active)->first();

        $headers = ['Accept' => 'application/json'];
        $url = '/api/auth/register';
        $data = [
            'invitation_code' => $invitation->registration_code,
            'fullname' => 'Test User',
            'birth_date' => '1998-05-05',
            'id_card_type' => 1,
            'id_card_number' => '3315130505980002',

            'phone' => '99999999999',
            'address' => 'St. Road Way 123',
            'email' => 'test@useronline.com',
            'password' => 'AnotherPassword123!',
            'confirm_password' => 'AnotherPassword123!',
            'bank_name' => 'Bank Name Information',
            'bic_code' => '001',
            'bank_account' => '019019019019',
            'bank_holder_name' => 'Test User',
            'profile_picture' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
            'house_number' => 12,
            'zipcode' => 712711,
            'city' => 'Some City',
            'province' => 'Some Province',
        ];
        $response = $this->withHeaders($headers)->post($url, $data);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test wrong input register
     *
     * @return void
     */
    public function test_wrong_input_register()
    {
        $invitation = RegisterInvitation::where('status', RegisterInvitationStatus::Active)->first();
        
        $headers = ['Accept' => 'application/json'];
        $url = '/api/auth/register';
        $data = [
            'invitation_code' => $invitation->registration_code,
            'fullname' => 'Test User',
            'birth_date' => '1998-05-05',
            'id_card_type' => 1,
            'id_card_number' => '3315130505980002',

            'phone' => '99999999999',
            'address' => 'St. Road Way 123',
            'email' => 'test@useronline.com',
            'password' => 'AnotherPassword123!',
            'confirm_password' => 'AnotherPassword123!',
            'bank_name' => 'Bank Name Information',
            'bic_code' => '001',
            'bank_account' => '019019019019',
            'bank_holder_name' => 'Test User',
            'profile_picture' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
            'house_number' => 12,
            'zipcode' => 712711,
        ];
        $response = $this->withHeaders($headers)->post($url, $data);

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors');
        });
    }

    /**
     * Test without invitation code
     *
     * @return void
     */
    public function test_without_invitation_code()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/auth/register';
        $data = [
            'fullname' => 'Test User',
            'birth_date' => '1998-05-05',
            'id_card_type' => 1,
            'id_card_number' => '3315130505980002',

            'phone' => '99999999999',
            'address' => 'St. Road Way 123',
            'email' => 'test' . generateUuid() . '@useronline.com',
            'password' => 'AnotherPassword123!',
            'confirm_password' => 'AnotherPassword123!',
            'bank_name' => 'Bank Name Information',
            'bic_code' => '001',
            'bank_account' => '019019019019',
            'bank_holder_name' => 'Test User',
            'profile_picture' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
            'house_number' => 12,
            'zipcode' => 712711,
            'city' => 'Some City',
            'province' => 'Some Province',
        ];
        $response = $this->withHeaders($headers)->post($url, $data);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }
}
