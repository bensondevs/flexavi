<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Owner;

class CompanyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test if a user with no company owned try to view their company.
     *
     * @return void
     */
    public function test_non_owner_user_view_company()
    {
        $user = User::whereDoesntHave('owner')->first();
        $token = $user->generateToken();
        
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/user';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(403);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
    }

    /**
     * Test if a user with company owned try to view their company.
     *
     * @return void
     */
    public function test_owner_user_view_company()
    {
        $user = User::whereHas('owner')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/user';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('company');
        });
    }

    /**
     * Test if a user with no company owned try to register new company.
     *
     * @return void
     */
    public function test_non_user_owner_register_company()
    {
        $user = User::whereDoesntHave('owner')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/register';
        $registerData = [
            'visiting_address_street' => '11, Visited Address Street',
            'visiting_address_house_number' => 11,
            'visiting_address_house_number_suffix' => 'A',
            'visiting_address_zipcode' => 111111,
            'visiting_address_city' => 'Visited City',

            'invoicing_address_street' => '22, Invoiced Address Street',
            'invoicing_address_house_number' => 22,
            'invoicing_address_house_number_suffix' => 'B',
            'invoicing_address_zipcode' => 222222,
            'invoicing_address_city' => 'Invoiced City',

            'company_name' => 'Another Company',
            'email' => 'company@flexavi.com',
            'phone_number' => '333111222000',
            'vat_number' => '111000222111',
            'commerce_chamber_number' => 121,
            'company_website_url' => 'https://company.test.com/',
        ];
        $response = $this->withHeaders($headers)->post($url, $registerData);

        $response->assertStatus(403);
    }

    /**
     * Test if a user with company owned try to register new company.
     *
     * @return void
     */
    public function test_user_owner_register_company()
    {
        $user = User::whereHas('owner')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/register';
        $registerData = [
            'visiting_address_street' => '11, Visited Address Street',
            'visiting_address_house_number' => 11,
            'visiting_address_house_number_suffix' => 'A',
            'visiting_address_zipcode' => 111111,
            'visiting_address_city' => 'Visited City',

            'invoicing_address_street' => '22, Invoiced Address Street',
            'invoicing_address_house_number' => 22,
            'invoicing_address_house_number_suffix' => 'B',
            'invoicing_address_zipcode' => 222222,
            'invoicing_address_city' => 'Invoiced City',

            'company_name' => 'Another Company',
            'email' => 'company@flexavi.com',
            'phone_number' => '333111222000',
            'vat_number' => '111000222111',
            'commerce_chamber_number' => 121,
            'company_website_url' => 'https://company.test.com/',
        ];
        $response = $this->withHeaders($headers)->post($url, $registerData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test if a owner can update their company.
     *
     * @return void
     */
    public function test_owner_can_update_company()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/update';
        $companyData = [
            'visiting_address_street' => '11, Visited Address Street',
            'visiting_address_house_number' => 11,
            'visiting_address_house_number_suffix' => 'A',
            'visiting_address_zipcode' => 111111,
            'visiting_address_city' => 'Visited City',

            'invoicing_address_street' => '22, Invoiced Address Street',
            'invoicing_address_house_number' => 22,
            'invoicing_address_house_number_suffix' => 'B',
            'invoicing_address_zipcode' => 222222,
            'invoicing_address_city' => 'Invoiced City',

            'company_name' => 'Another Company',
            'email' => 'company@flexavi.com',
            'phone_number' => '333111222000',
            'vat_number' => '111000222111',
            'commerce_chamber_number' => 121,
            'company_website_url' => 'https://company.test.com/',
        ];
        $response = $this->withHeaders($headers)->patch($url, $companyData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test if a owner can upload company logo.
     *
     * @return void
     */
    public function test_owner_can_upload_company_logo()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/upload_logo';
        $photoData = [
            'company_logo' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
        ];
        $response = $this->withHeaders($headers)->post($url, $photoData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }
}