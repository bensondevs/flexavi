<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Owner, Customer, Company };

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = '/api/dashboard/companies/customers';

    /**
     * A load all customers test.
     *
     * @return void
     */
    public function test_view_all_customers()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);
        
        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('customers');
            $json->has('customers.data');
        });
    }

    /**
     * A load all trashed customers test.
     *
     * @return void
     */
    public function test_view_trashed_customers()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/trasheds';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('customers');
            $json->has('customers.data');
        });
    }

    /**
     * A store customer test.
     *
     * @return void
     */
    public function test_store_customer()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customerData = [
            'fullname' => 'Customer Full Name',
            'email' => 'customer@example.mail',
            'phone' => '08628297123',
            'second_phone' => '087279615114',
            'address' => 'Example Address Online',
            'house_number' => 12,
            'house_number_suffix' => 'A',
            'zipcode' => 87211,
            'city' => 'Example City',
            'province' => 'Example Province',
        ];
        $url = $this->baseUrl . '/store';
        $response = $this->json('POST', $url, $customerData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A view customer test.
     *
     * @return void
     */
    public function test_view_customer()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = $this->baseUrl . '/view?id=' . $customer->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('customer');
        });
    }

    /**
     * A update customer test.
     *
     * @return void
     */
    public function test_update_customer()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $customerData = [
            'id' => $customer->id,
            'fullname' => 'Customer Full Name',
            'email' => 'customer@example.mail',
            'phone' => '08628297123',
            'second_phone' => '087279615114',
            'address' => 'Example Address Online',
            'house_number' => 12,
            'house_number_suffix' => 'A',
            'zipcode' => 87211,
            'city' => 'Example City',
            'province' => 'Example Province',
        ];
        $url = $this->baseUrl . '/update';
        $response = $this->json('PATCH', $url, $customerData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A delete customer test.
     *
     * @return void
     */
    public function test_delete_customer()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = $this->baseUrl . '/delete';
        $response = $this->json('DELETE', $url, ['id' => $customer->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A restore customer test.
     *
     * @return void
     */
    public function test_restore_customer()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->softDeleted()->create();

        $customerData = ['id' => $customer->id];
        $url = $this->baseUrl . '/restore';
        $response = $this->json('PATCH', $url, $customerData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A load customer appointments test.
     *
     * @return void
     */
    public function test_view_customer_appointments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = $this->baseUrl . '/appointments?customer_id=' . $customer->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('appointments');
        });
    }

    /**
     * A load customer quotations test.
     *
     * @return void
     */
    public function test_view_customer_quotations()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = $this->baseUrl . '/quotations?customer_id=' . $customer->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotations');
        });
    }
}
