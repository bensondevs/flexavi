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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);
        
        $url = $this->baseUrl;
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/trasheds';
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
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
        $response = $this->post($url, $customerData);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $url = $this->baseUrl . '/view?id=' . $customer->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

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
        $response = $this->patch($url, $customerData);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $customerData = ['id' => $customer->id];
        $url = $this->baseUrl . '/delete';
        $response = $this->delete($url, $customerData);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->onlyTrashed()->inRandomOrder()->first() ?:
            Customer::factory()->softDeleted()->create(['company_id' => $company->id]);

        $customerData = ['id' => $customer->id];
        $url = $this->baseUrl . '/restore';
        $response = $this->patch($url, $customerData);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $url = $this->baseUrl . '/appointments?customer_id=' . $customer->id;
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $url = $this->baseUrl . '/quotations?customer_id=' . $customer->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotations');
        });
    }
}
