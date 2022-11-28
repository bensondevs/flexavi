<?php

namespace Tests\Integration\Dashboard\Company\Company;

use Database\Factories\CompanyFactory;
use Database\Factories\OwnerFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\CompanyController::update()
 *      to the tested controller
 */
class UpdateCompanyLogoTest extends TestCase
{
    use WithFaker;

    /**
     * Test update company logo
     *
     * @return void
     */
    public function test_update_company_logo()
    {
        $company = CompanyFactory::new()->create();
        $mainOwner = OwnerFactory::new()->for($company)->notPrime()->create();
        $this->actingAs($mainOwner->user);

        // company logo url before updated
        $companyLogoURL = $company->logo_url ;

        $input = [
            'logo' =>  UploadedFile::fake()
            ->image('logo.png', 50, 50)
            ->size(100),
        ];

        $response = $this->postJson(
            '/api/dashboard/companies/logo',
            $input
        );

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('company');
            $json->whereNot('company.company_logo_url', null);
            $json->has('status');
            $json->has('message');
        });
    }
}
