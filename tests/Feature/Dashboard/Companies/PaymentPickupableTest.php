<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    Company, 
    Owner, 
    PaymentPickup, 
    PaymentPickupable,
    Appointment, 
    Invoice, 
    Revenue 
};

class PaymentPickupableTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tested module base url
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/payment_pickups/pickupables';

    /**
     * Add invoice as payment pickupable test
     * 
     * @return void
     */
    public function test_add_invoice_as_payment_pickupable()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/add';
        $response = $this->json('POST', $url, [
            'payment_pickup_id' => PaymentPickup::factory()
                ->for($company)
                ->create()
                ->id,
            'invoice_id' => Invoice::factory()
                ->for($company)
                ->create()
                ->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Add revenue as payment pickupable test
     * 
     * @return void
     */
    public function test_add_revenue_as_payment_pickupable()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/add';
        $response = $this->json('POST', $url, [
            'payment_pickup_id' => PaymentPickup::factory()
                ->for($company)
                ->create()
                ->id,
            'revenue_id' => Revenue::factory()
                ->for($company)
                ->create()
                ->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Add payment term as payment pickupable test
     * 
     * @return void
     */
    public function test_add_payment_term_as_payment_pickupable()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/add';
        $response = $this->json('POST', $url, [
            'payment_pickup_id' => PaymentPickup::factory()
                ->for($company)
                ->create()
                ->id,
            'payment_term_id' => PaymentTerm::factory()
                ->for($company)
                ->create()
                ->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Add multiple payment pickup-pickupables test
     * 
     * @return void
     */
    public function test_add_multiple_payment_pickup_pickupables()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $invoices = Invoice::factory()
            ->for($company)
            ->count(rand(0, 10))
            ->create();
        $revenues = Revenue::factory()
            ->for($company)
            ->count(rand(0, 10))
            ->create();
        $paymentTerms = PaymentTerm::factory()
            ->for($company)
            ->count(rand(0, 10))
            ->create();

        $filterFunction = function ($pickupable) {
            return $pickupable->id;
        };

        $url = $this->baseUrl . '/add_multiple';
        $response = $this->json('POST', $url, [
            'payment_pickup_id' => PaymentPickup::factory()
                ->for($company)
                ->create()
                ->id,
            'invoice_ids' => array_map($filterFunction, $invoices),
            'revenue_ids' => array_map($filterFunction, $revenues),
            'payment_term_ids' => array_map($filterFunction, $paymentTerms),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Remove payment pickupable with invoice type test
     * 
     * @return void
     */
    public function test_remove_invoice_payment_pickupable()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $paymentPickup = PaymentPickup::factory()
            ->for($company)
            ->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/remove';
        $response = $this->json('DELETE', $url, [
            'payment_pickup_id' => $paymentPickup->id,
            'invoice_id' => $invoice->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Remove payment pickupable with revenue type test
     * 
     * @return void
     */
    public function test_remove_revenue_payment_pickupable()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $paymentPickup = PaymentPickup::factory()
            ->for($company)
            ->create();
        $revenue = Revenue::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/remove';
        $response = $this->json('DELETE', $url, [
            'payment_pickup_id' => $paymentPickup->id,
            'revenue_id' => $revenue->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Remove payment pickupable with payment term type test
     * 
     * @return void
     */
    public function test_remove_payment_term_pickupable()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $paymentPickup = PaymentPickup::factory()
            ->for($company)
            ->create();
        $paymentTerm = Revenue::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/remove';
        $response = $this->json('DELETE', $url, [
            'payment_pickup_id' => $paymentPickup->id,
            'payment_term_id' => $paymentTerm->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Remove multiple payment pickup-pickupable test
     * 
     * @return void
     */
    public function test_remove_multiple_payment_pickup_pickupable()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);
        
        $paymentPickup = PaymentPickup::factory()
            ->for($company)
            ->create();
        $invoicePickupables = PaymentPickupable::factory()
            ->for($paymentPickup)
            ->invoice()
            ->count(rand(3, 10))
            ->create();
        $paymentTermPickupables = PaymentPickupable::factory()
            ->for($paymentPickup)
            ->paymentTerm()
            ->count(rand(3, 10))
            ->create();
        $revenuePickupables = PaymentPickupable::factory()
            ->for($paymentPickup)
            ->revenue()
            ->count(rand(3, 10))
            ->create();

        $url = $this->baseUrl . '/remove_multiple';
        $response = $this->json('DELETE', $url, [
            'payment_pickup_id' => $paymentPickup->id,
            'invoice_ids' => array_map(function ($pickupable) {
                return $pickupable->payment_pickupable_id;
            }, $invoicePickupables),
            'payment_term_ids' => array_map(function ($pickupable) {
                return $pickupable->payment_pickupable_id;
            }, $paymentTermPickupables),
            'revenue_ids' => array_map(function ($pickupable) {
                return $pickupable->payment_pickupable_id;
            }, $revenuePickupables),
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Truncate payment pickup-pickupables test
     * 
     * @return void
     */
    public function test_truncate_payment_pickup_pickupables()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $paymentPickup = PaymentPickup::factory()
            ->for($company)
            ->create();

        $url = $this->baseUrl . '/truncate';
        $response = $this->json('DELETE', $url, [
            'payment_pickup_id' => $paymentPickup->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
