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

class PaymentPickupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tested module base url
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/payment_pickups';

    /**
     * Populate payment pickups test.
     *
     * @return void
     */
    public function test_populate_company_payment_pickups()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_pickups');
        });
    }

    /**
     * Populate appointment payment pickups test.
     * 
     * @return void
     */
    public function test_populate_appointment_payment_pickups()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $appointment = Appointment::factory()
            ->has(PaymentPickup::factory()->count(10))
            ->create();

        $url = $this->baseUrl . '/appointment?appointment_id=' . $appointment->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_pickups');
        });
    }

    /**
     * Store payment pickup test
     * 
     * @return void
     */
    public function test_store_payment_pickup()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $input = [
            'company_id' => $company->id,
            'appointment_id' => Appointment::factory()
                ->for($company)
                ->create()
                ->id,
            'should_pickup_amount' => ($shouldPickupAmount = rand(10, 1000)),
            'picked_up_amount' => rand(1, $shouldPickupAmount),
        ];
        if ($input['should_pickup_amount'] !== $input['picked_up_amount']) {
            $input['reason_not_all'] = 'Example reason not all';
        }

        $url = $this->baseUrl . '/store';
        $response = $this->json('POST', $url, $input);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Update payment pickup test
     * 
     * @return void
     */
    public function test_update_payment_pickup()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/update';
        $response = $this->json('PATCH', $url, [
            'should_pickup_amount' => rand(20, 100),
            'picked_up_amount' => rand(10, 20),
            'reason_not_all' => 'Reason not all example',
            'should_picked_up_at' => now(),
            'picked_up_at' => now(),
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Delete payment pickup test
     * 
     * @return void
     */
    public function test_delete_payment_pickup()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $paymentPickup = PaymentPickup::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/delete';
        $response = $this->json('DELETE', $url, [
            'payment_pickup_id' => $paymentPickup->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Restore payment pickup test
     * 
     * @return void
     */
    public function test_restore_payment_pickup()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $paymentPickup = PaymentPickup::factory()
            ->for($company)
            ->softDeleted()
            ->create();
        $url = $this->baseUrl . '/restore';
        $response = $this->json('PATCH', $url, [
            'payment_pickup_id' => $paymentPickup->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
