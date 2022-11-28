<?php

namespace Tests\Feature\Dashboard\Company\Customer;

use App\Enums\Customer\{CustomerAcquisition, CustomerSalutation};
use App\Http\Resources\Customer\CustomerResource;
use App\Models\{Customer\Customer, User\User};
use App\Models\Address\Address;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Customer\CustomerController
 *      To the tested controller class.
 */
class CustomerTest extends TestCase
{
    use WithFaker;

    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/dashboard/companies/customers';

    /**
     * Authenticate the tester user to access the endpoint.
     *
     * @return User
     */
    private function authenticate(): User
    {
        $user = User::factory()->owner()->create();
        $user->refresh()->load(['owner.company']);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Assert populate request succeed.
     *
     * @param TestResponse $response
     * @return void
     */
    private function assertPopulateRequestSucceed(TestResponse $response): void
    {
        // Assert response status is 200
        $response->assertOk();

        // Assert response content is as expected.
        $response->assertJson(function (AssertableJson $json) {
            $json->has('customers');
            $json->whereType('customers.data', 'array');

            // pagination meta attributes
            $paginationMetaAttributes = [
                'current_page',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ];
            foreach ($paginationMetaAttributes as $paginationMetaAttribute) {
                $json->has('customers.' . $paginationMetaAttribute);
            }
        });
    }

    /**
     * Test populate company customers
     *
     * @test
     * @return void
     */
    public function test_populate_company_customers(): void
    {
        $this->authenticate();

        $response = $this->getJson(self::MODULE_BASE_URL);
        $this->assertPopulateRequestSucceed($response);
    }

    /**
     * Test populate company trashed customers
     *
     * @test
     * @return void
     */
    public function test_populate_company_trashed_customers(): void
    {
        $this->authenticate();

        $response = $this->getJson(self::MODULE_BASE_URL . '/trasheds');
        $this->assertPopulateRequestSucceed($response);
    }

    /**
     * Assert returned response has customer resource instance.
     *
     * @param TestResponse $response
     * @return void
     */
    private function assertResponseHasCustomerResource(TestResponse $response): void
    {
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('customer', $content);

        $customer = $content['customer'];
        $this->assertInstanceOf(CustomerResource::class, $customer);
    }

    /**
     * Test get a company customer
     *
     * @test
     * @return void
     */
    public function test_get_company_customer(): void
    {
        $user = $this->authenticate();
        $company = $user->company;

        $customer = Customer::factory(state: [
            'company_id' => $company->id,
        ])->create();

        $url = urlWithParams(self::MODULE_BASE_URL . '/view', [
            'id' => $customer->id,
        ]);
        $response = $this->getJson($url);
        $response->assertOk();
        $this->assertResponseHasCustomerResource($response);
    }

    /**
     * Assert response has success attributes.
     *
     * @param TestResponse $response
     * @return void
     */
    private function assertResponseHasSuccessAttributes(TestResponse $response): void
    {
        $content = $response->getOriginalContent();

        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('message', $content);

        $this->assertEquals('success', $content['status']);
    }

    /**
     * Test store a company customer
     *
     * @test
     * @return void
     */
    public function test_store_company_customer(): void
    {
        $user = $this->authenticate();

        $input = [
            'fullname' => 'Customer Full Name',
            'email' => random_string() . $this->faker->safeEmail,
            'phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
            'second_phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
            'acquired_by' => $user->id,
            'acquired_through' => CustomerAcquisition::Call,
            'salutation' => CustomerSalutation::Mr,
            'address' => 'Example Address Online',
            'house_number' => '12',
            'house_number_suffix' => 'A',
            'zipcode' => '87211',
            'city' => 'Example City',
            'province' => 'Example Province',
        ];
        $response = $this->postJson(self::MODULE_BASE_URL . '/store', $input);
        $response->assertCreated();
        $this->assertResponseHasCustomerResource($response);
        $this->assertResponseHasSuccessAttributes($response);

        // get created customer
        $customer = Customer::query()->whereEmail($input['email'])->first();
        $this->assertNotNull($customer);

        // assert customer address is created
        $this->assertDatabaseHas(
            (new Address())->getTable(),
            array_merge(
                ['addressable_type' => Customer::class , 'addressable_id' => $customer->id],
                Arr::only($input, [
                    'address',
                    'house_number',
                    'house_number_suffix',
                    'zipcode',
                    'city',
                    'province'
                ])
            )
        );
    }

    /**
     * Test store a company customer with autofill address
     *
     * @test
     * @return void
     */
    public function test_store_company_customer_with_autofill_address(): void
    {
        $user = $this->authenticate();
        $input = [
            'fullname' => 'Customer Full Name',
            'email' => random_string() . $this->faker->safeEmail,
            'phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
            'second_phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
            'acquired_by' => $user->id,
            'acquired_through' => CustomerAcquisition::Call,
            'salutation' => CustomerSalutation::Mr,
            // this is a valid address, don't touch it
            'house_number' => '28',
            'zipcode' => '5212GG',
        ];
        $response = $this->postJson(self::MODULE_BASE_URL . '/store', $input);
        $response->assertCreated();
        $this->assertResponseHasCustomerResource($response);
        $this->assertResponseHasSuccessAttributes($response);

        // get created customer
        $customer = Customer::query()->whereEmail($input['email'])->first();
        $this->assertNotNull($customer);

        // assert customer address is created
        $this->assertDatabaseHas(
            (new Address())->getTable(),
            array_merge(
                ['addressable_type' => Customer::class , 'addressable_id' => $customer->id],
                Arr::only($input, ['house_number', 'zipcode'])
            )
        );
    }

    /**
     * Test update a company customer
     *
     * @test
     * @return void
     */
    public function test_update_company_customer(): void
    {
        $user = $this->authenticate();

        $customer = Customer::factory(state: [
            'company_id' => $user->company->id,
        ])->create();

        $response = $this->putJson(self::MODULE_BASE_URL . '/update', [
            'id' => $customer->id,
            'fullname' => 'Customer Full Name',
            'email' => random_string() . $this->faker->safeEmail,
            'phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
            'second_phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
            'acquired_by' => $user->id,
            'acquired_through' => CustomerAcquisition::Call,
            'salutation' => CustomerSalutation::Mr,
            'address' => 'Example Address Online',
            'house_number' => '12',
            'house_number_suffix' => 'A',
            'zipcode' => '87211',
            'city' => 'Example City',
            'province' => 'Example Province',
        ]);

        $response->assertStatus(200);
        $this->assertResponseHasCustomerResource($response);
        $this->assertResponseHasSuccessAttributes($response);
    }

    /**
     * Test update a company customer with autofill address
     *
     * @test
     * @return void
     */
    public function test_update_company_customer_with_autofill_address(): void
    {
        $user = $this->authenticate();

        $customer = Customer::factory(state: [
            'company_id' => $user->company->id,
        ])->create();

        $response = $this->putJson(
            self::MODULE_BASE_URL . '/update',
            [
                'id' => $customer->id,
                'fullname' => 'Customer Full Name',
                'email' => random_string() . $this->faker->safeEmail,
                'phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
                'second_phone' => preg_replace("/[^0-9]/", "", $this->faker->phoneNumber),
                'acquired_by' => $user->id,
                'acquired_through' => CustomerAcquisition::Call,
                'salutation' => CustomerSalutation::Mr,
                'house_number' => '99',
                'zipcode' => '1011AB',
            ]
        );

        $response->assertStatus(200);
        $this->assertResponseHasCustomerResource($response);
        $this->assertResponseHasSuccessAttributes($response);
    }

    /**
     * Test delete a company customer
     *
     * @test
     * @return void
     */
    public function test_delete_company_customer(): void
    {
        $user = $this->authenticate();

        $customer = Customer::factory(state: [
            'company_id' => $user->company->id,
        ])->create();

        $response = $this->deleteJson(self::MODULE_BASE_URL . '/delete', [
            'id' => $customer->id,
        ]);
        $response->assertStatus(200);
        $this->assertResponseHasSuccessAttributes($response);
    }

    /**
     * Test delete a company customer permanently
     *
     * @return void
     */
    public function test_delete_company_customer_permanently(): void
    {
        $user = $this->authenticate();

        $customer = Customer::factory(state: [
            'company_id' => $user->company->id,
        ])->create();

        $response = $this->deleteJson(self::MODULE_BASE_URL . '/delete', [
            'id' => $customer->id,
            'force' => true,
        ]);

        $response->assertStatus(200);
        $this->assertResponseHasSuccessAttributes($response);
    }

    /**
     * Test restore a company trashed customer
     *
     * @test
     * @return void
     */
    public function test_restore_company_trashed_customer(): void
    {
        $user = $this->authenticate();

        $customer = Customer::factory(state: [
            'company_id' => $user->company->id,
            'deleted_at' => now(),
        ])->create();

        $response = $this->putJson(self::MODULE_BASE_URL . '/restore', [
            'id' => $customer->id,
        ]);
        $response->assertStatus(200);
        $this->assertResponseHasCustomerResource($response);
        $this->assertResponseHasSuccessAttributes($response);
    }
}
