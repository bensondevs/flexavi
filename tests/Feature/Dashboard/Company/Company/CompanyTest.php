<?php

namespace Tests\Feature\Dashboard\Company\Company;

use App\Models\{User\User};
use App\Models\Company\Company;
use Database\Factories\CompanyFactory;
use Database\Factories\OwnerFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use WithFaker;

    /**
     * Test store company
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\CompanyController::store()
     *      the tested controller method
     */
    public function test_store(): void
    {
        $owner = OwnerFactory::new()->withoutCompany()->create();
        $this->actingAs($owner->user);

        $input = [
            "company_name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone_number" => $this->faker->phoneNumber,
            "vat_number" => $this->faker->numberBetween(10, 100),
            "commerce_chamber_number" => $this->faker->numberBetween(10, 100),
            "company_website_url" => $this->faker->url,

            "visiting_address" => [
                "address" => $this->faker->address,
                "house_number" => $this->faker->numberBetween(1, 100),
                "house_number_suffix" => $this->faker->word,
                "province" => $this->faker->word,
                "zipcode" => $this->faker->numberBetween(1000, 5000),
                "city" => $this->faker->city,

            ],

            "invoicing_address" => [
                "address" => $this->faker->address,
                "house_number" => $this->faker->numberBetween(1, 100),
                "house_number_suffix" => $this->faker->word,
                "province" => $this->faker->word,
                "zipcode" => $this->faker->numberBetween(1000, 5000),
                "city" => $this->faker->city,

            ]
        ];

        $response = $this->postJson(
            '/api/dashboard/companies/store',
            $input
        );

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('company');
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test view current user's company
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\CompanyController::view()
     *      the tested controller method
     */
    public function test_view_current_user_company()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $company = $user->company;

        $response = $this->getJson('/api/dashboard/companies/self');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($company) {
            $json->where("company.company_name", $company->company_name);
            $json->has("has_company");
        });
    }


    /**
     * Test upload company logo
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\CompanyController::uploadLogo()
     *      the tested controller method
     */
    public function test_upload_logo()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $companyLogoUrl = $company->logo_url; // previous value of company's logo_url
        $this->assertEquals(null, $companyLogoUrl);

        $response = $this->postJson(
            '/api/dashboard/companies/logo',
            [
                "logo" => UploadedFile::fake()->image("logo.png", 50, 50),
            ]
        );

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('company');
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test update company
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\CompanyController::update()
     *      the tested controller method
     */
    public function test_update()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        // before updated values
        $company = $user->owner->company;
        $visitingAddress = $company->visitingAddress;
        $invoicingAddress = $company->invoicingAddress;

        // after updated values
        $input = [
            "company_name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone_number" => $this->faker->phoneNumber,
            "vat_number" => $this->faker->numberBetween(10, 100),
            "commerce_chamber_number" => $this->faker->numberBetween(10, 100),
            "company_website_url" => $this->faker->url,

            "visiting_address" => [
                "address" => $this->faker->address,
                "house_number" => $this->faker->numberBetween(1, 100),
                "house_number_suffix" => $this->faker->word,
                "province" => $this->faker->word,
                "zipcode" => $this->faker->numberBetween(1000, 5000),
                "city" => $this->faker->city,
            ],

            "invoicing_address" => [
                "address" => $this->faker->address,
                "house_number" => $this->faker->numberBetween(1, 100),
                "house_number_suffix" => $this->faker->word,
                "province" => $this->faker->word,
                "zipcode" => $this->faker->numberBetween(1000, 5000),
                "city" => $this->faker->city,
            ]
        ];

        $response = $this->putJson(
            '/api/dashboard/companies/update',
            $input
        );

        // make sure company is updated
        $this->assertEquals($company->fresh()->company_name, $input["company_name"]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test soft delete company
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\CompanyController::delete
     *      the tested controller method
     */
    public function test_soft_delete(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $this->assertEquals($user->company->id, $company->id);

        $response = $this->deleteJson(
            "/api/dashboard/companies/delete",
            ['force' => false]
        );

        $this->assertSoftDeleted((new Company())->getTable(), ["id" => $company->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test hard delete company
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\CompanyController::delete
     *      the tested controller method
     */
    public function test_hard_delete(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $response = $this->deleteJson(
            "/api/dashboard/companies/delete",
            ['force' => true]
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test restore company
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\CompanyController::restore()
     *      the tested controller method
     */
    public function test_restore(): void
    {
        // create a user without company
        $owner = OwnerFactory::new()->withoutCompany()->create();
        $user = $owner->user;

        // create a new company
        $company = CompanyFactory::new()->create();

        // soft delete the company
        $company->delete();

        //  assign the company to the user
        $owner->company_id = $company->id;
        $owner->save();

        //  login the user
        $this->actingAs($user);

        $response = $this->patchJson("/api/dashboard/companies/restore");

        $this->assertNull($company->fresh()->deleted_at);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }
}
