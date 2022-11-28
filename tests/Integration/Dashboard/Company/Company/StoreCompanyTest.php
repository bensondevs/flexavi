<?php

namespace Tests\Integration\Dashboard\Company\Company;

use App\Models\Address\Address;
use App\Models\Company\Company;
use Database\Factories\OwnerFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\CompanyController::store()
 *      to the tested controller
 */
class StoreCompanyTest extends TestCase
{
    use WithFaker;

    /**
     * Test store a company
     *
     * @return void
     */
    public function test_store_company()
    {
        $owner = OwnerFactory::new()->withoutCompany()->create();
        $this->assertTrue(!! $owner->company->delete());
        $this->assertNull($owner->fresh()->company);

        $this->actingAs($owner->user);

        $input = [
            "company_name" => $this->faker->name.' '.self::class,
            "email" => $this->faker->email,
            "phone_number" => $this->faker->phoneNumber,
            "vat_number" => $this->faker->numberBetween(10, 100),
            "commerce_chamber_number" => $this->faker->numberBetween(10, 100),
            "company_website_url" => $this->faker->url,

            "visiting_address" => [
                "address" => $this->faker->address.' '.self::class,
                "house_number" => $this->faker->numberBetween(1, 100),
                "house_number_suffix" => $this->faker->word,
                "province" => $this->faker->word,
                "zipcode" => $this->faker->numberBetween(1000, 5000),
                "city" => $this->faker->city,
            ],

            "invoicing_address" => [
                "address" => $this->faker->address.' '.self::class,
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

        $this->assertDatabaseHas(
            (new Company())->getTable(),
            Arr::only($input, [
                'company_name',
                'email',
                'phone_number',
                'vat_number',
                'commerce_chamber_number',
                'company_website_url'
            ])
        );

        $this->assertDatabaseHas(
            (new Address())->getTable(),
            $input['visiting_address'],
        );
        $this->assertDatabaseHas(
            (new Address())->getTable(),
            $input['invoicing_address'],
        );
    }

     /**
     * Test store a company with address autocomplete (pro6ppAddress)
     *
     * @return void
     */
    public function test_store_company_with_address_autocomplete()
    {
        $owner = OwnerFactory::new()->withoutCompany()->create();
        $this->assertTrue(!! $owner->company->delete());
        $this->assertNull($owner->fresh()->company);

        $this->actingAs($owner->user);

        $input = [
            "company_name" => $this->faker->name.' '.self::class,
            "email" => $this->faker->email,
            "phone_number" => $this->faker->phoneNumber,
            "vat_number" => $this->faker->numberBetween(10, 100),
            "commerce_chamber_number" => $this->faker->numberBetween(10, 100),
            "company_website_url" => $this->faker->url,

            "visiting_address" => [
                "house_number" => 5,
                "zipcode" => "5215VD"
            ],

            "invoicing_address" => [
                "house_number" => 180,
                "zipcode" => "5213AP"
            ]
        ];

        $response = $this->postJson('/api/dashboard/companies/store', $input);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('company');
            $json->has('status');
            $json->has('message');
        });

        // get created company
        $company = Company::query()->whereCompanyName($input['company_name'])->first();
        $this->assertNotNull($company);

        $this->assertDatabaseHas(
            (new Address())->getTable(),
            array_merge(
                ['addressable_id' => $company->id],
                Arr::only($input['visiting_address'], ['house_number', 'zipcode'])
            ),
        );
        $this->assertDatabaseHas(
            (new Address())->getTable(),
            array_merge(
                ['addressable_id' => $company->id],
                Arr::only($input['invoicing_address'], ['house_number', 'zipcode'])
            ),
        );
    }
}
