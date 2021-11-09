<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Owner, Company };

class CompanyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test if a user with company owned try to register new company.
     *
     * @return void
     */
    public function test_user_owner_register_company()
    {
        $owner = Owner::factory()->withoutCompany()->prime()->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = '/api/dashboard/companies/register';
        $response = $this->json('POST', $url, [
            'visiting_address' => '11, Visited Address Street',
            'visiting_address_house_number' => 11,
            'visiting_address_house_number_suffix' => 'A',
            'visiting_address_zipcode' => 111111,
            'visiting_address_city' => 'Visited City',
            'visiting_address_province' => 'Visiting Province',

            'invoicing_address' => '22, Invoiced Address Street',
            'invoicing_address_house_number' => 22,
            'invoicing_address_house_number_suffix' => 'B',
            'invoicing_address_zipcode' => 222222,
            'invoicing_address_city' => 'Invoiced City',
            'invoicing_address_province' => 'Invoicing Province',

            'company_name' => 'Another Company',
            'email' => 'company@flexavi.com',
            'phone_number' => '333111222000',
            'vat_number' => '111000222111',
            'commerce_chamber_number' => 121,
            'company_website_url' => 'https://company.test.com/',
        ]);

        $response->assertStatus(201);
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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/update';
        $response = $this->json('PATCH', $url, [
            'visiting_address' => '11, Visited Address Street',
            'visiting_address_house_number' => 11,
            'visiting_address_house_number_suffix' => 'A',
            'visiting_address_zipcode' => 111111,
            'visiting_address_city' => 'Visited City',
            'visiting_address_province' => 'Visiting Province',

            'invoicing_address' => '22, Invoiced Address Street',
            'invoicing_address_house_number' => 22,
            'invoicing_address_house_number_suffix' => 'B',
            'invoicing_address_zipcode' => 222222,
            'invoicing_address_city' => 'Invoiced City',
            'invoicing_address_province' => 'Invoicing Province',

            'company_name' => 'Another Company',
            'email' => 'company@flexavi.com',
            'phone_number' => '333111222000',
            'vat_number' => '111000222111',
            'commerce_chamber_number' => 121,
            'company_website_url' => 'https://company.test.com/',
        ]);

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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/upload_logo';
        $response = $this->json('POST', $url, [
            'company_logo' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }
}