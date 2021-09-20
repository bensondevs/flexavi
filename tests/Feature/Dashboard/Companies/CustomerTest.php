<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\Owner;
use App\Models\Customer;

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();
        
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/trasheds';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
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
        $response = $this->withHeaders($headers)->post($url, $customerData);

        $response->assertStatus(200);
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $customer = Customer::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/view?id=' . $customer->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $customer = Customer::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
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
        $response = $this->withHeaders($headers)->patch($url, $customerData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $customer = Customer::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $customerData = [
            'id' => $customer->id,
        ];
        $url = $this->baseUrl . '/delete';
        $response = $this->withHeaders($headers)->delete($url, $customerData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        if (! $customer = Customer::onlyTrashed()->where('company_id', $owner->company_id)->first()) {
            $customer = Customer::where('company_id', $owner->company_id)->first();
            $customerId = $customer->id;
            $customer->delete();

            $customer = Customer::onlyTrashed()->find($customerId);
        }

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $customerData = [
            'id' => $customer->id,
        ];
        $url = $this->baseUrl . '/restore';
        $response = $this->withHeaders($headers)->patch($url, $customerData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }
}
