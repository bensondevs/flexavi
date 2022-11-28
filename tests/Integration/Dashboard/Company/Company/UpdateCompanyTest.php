<?php

namespace Tests\Integration\Dashboard\Company\Company;

use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\CompanyController::update()
 *      to the tested controller
 */
class UpdateCompanyTest extends TestCase
{
    use WithFaker;

    /**
     * Test update company
     *
     * @return void
     */
    public function test_update_company()
    {
        $user = User::factory()->owner()->create();
        $user->owner->is_prime_owner = true;
        $user->owner->save();

        $this->actingAs($user);

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

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        // get updated company
        $updatedCompany = $company->fresh()->load('addresses');
        $this->assertEquals($user->owner->company->id , $updatedCompany->id ) ;

        // make sure company is updated
        $this->assertEquals($updatedCompany->company_name, $input["company_name"]);
        $this->assertEquals($updatedCompany->email, $input["email"]);
        $this->assertEquals($updatedCompany->phone_number, $input["phone_number"]);
        $this->assertEquals($updatedCompany->commerce_chamber_number, $input["commerce_chamber_number"]);
        $this->assertEquals($updatedCompany->company_website_url, $input["company_website_url"]);

        // assertions of company's visiting_address
        $this->assertEquals(
            $updatedCompany->visiting_address->address,
            $input['visiting_address']['address']
        );
        $this->assertEquals(
            $updatedCompany->visiting_address->house_number,
            $input['visiting_address']['house_number']
        );
        $this->assertEquals(
            $updatedCompany->visiting_address->house_number_suffix,
            $input['visiting_address']['house_number_suffix']
        );
        $this->assertEquals(
            $updatedCompany->visiting_address->zipcode,
            $input['visiting_address']['zipcode']
        );
        $this->assertEquals(
            $updatedCompany->visiting_address->city,
            $input['visiting_address']['city']
        );

        // assertions of company's invoicing_address
        $this->assertEquals(
            $updatedCompany->invoicing_address->address,
            $input['invoicing_address']['address']
        );
        $this->assertEquals(
            $updatedCompany->invoicing_address->house_number,
            $input['invoicing_address']['house_number']
        );
        $this->assertEquals(
            $updatedCompany->invoicing_address->house_number_suffix,
            $input['invoicing_address']['house_number_suffix']
        );
        $this->assertEquals(
            $updatedCompany->invoicing_address->zipcode,
            $input['invoicing_address']['zipcode']
        );
        $this->assertEquals(
            $updatedCompany->invoicing_address->city,
            $input['invoicing_address']['city']
        );
    }
}
