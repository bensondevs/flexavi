<?php

namespace Tests\Feature\Dashboard\Company\PaymentPickup;

use App\Models\Appointment\Appointment;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\PaymentPickup\PaymentPickup;
use App\Models\PaymentPickup\PaymentTerm;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PaymentPickupTest extends TestCase
{
    use WithFaker;

    /**
     * Test populate company payment_pickups
     *
     * @return void
     */
    public function test_populate_company_payment_pickups()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson(
            '/api/dashboard/companies/payment_pickups'
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_pickups');
            $json->whereType('payment_pickups.data', 'array');

            // pagination meta
            $json->has('payment_pickups.current_page');
            $json->has('payment_pickups.first_page_url');
            $json->has('payment_pickups.from');
            $json->has('payment_pickups.last_page');
            $json->has('payment_pickups.last_page_url');
            $json->has('payment_pickups.links');
            $json->has('payment_pickups.next_page_url');
            $json->has('payment_pickups.path');
            $json->has('payment_pickups.per_page');
            $json->has('payment_pickups.prev_page_url');
            $json->has('payment_pickups.to');
            $json->has('payment_pickups.total');
        });
    }

    /**
     * Test populate company trashed payment_pickups
     *
     * @return void
     */
    public function test_populate_company_trashed_payment_pickups()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson(
            '/api/dashboard/companies/payment_pickups/trasheds'
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_pickups');
            $json->whereType('payment_pickups.data', 'array');

            // pagination meta
            $json->has('payment_pickups.current_page');
            $json->has('payment_pickups.first_page_url');
            $json->has('payment_pickups.from');
            $json->has('payment_pickups.last_page');
            $json->has('payment_pickups.last_page_url');
            $json->has('payment_pickups.links');
            $json->has('payment_pickups.next_page_url');
            $json->has('payment_pickups.path');
            $json->has('payment_pickups.per_page');
            $json->has('payment_pickups.prev_page_url');
            $json->has('payment_pickups.to');
            $json->has('payment_pickups.total');
        });
    }

    /**
     * Test store payment pickup
     *
     * @return void
     */
    public function test_store_payment_pickup()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->createOneQuietly();

        $items = [];
        for ($i = 0; $i < rand(1, 2); $i++) {
            $invoice = Invoice::factory()
                ->for($customer)
                ->for($user->owner->company)
                ->create();

            $paymentTerms = PaymentTerm::factory()
                ->for($invoice)
                ->for($company)
                ->count(rand(1, 3))->create();

            array_push($items, [
                'invoice_id' => $invoice->id,
                'pickup_amount' => rand(100, 200),
                'note' => $this->faker->sentence,
                'payment_term_ids' => $paymentTerms->pluck('id')->toArray()
            ]);
        }

        $input = [
            'company_id' => $appointment->company_id,
            'appointment_id' => $appointment->id,
            'items' => $items
        ];

        $response = $this->postJson('/api/dashboard/companies/payment_pickups/store', $input);

        $this->assertDatabaseHas((new PaymentPickup())->getTable(), [
            'company_id' => $company->id,
            'appointment_id' => $appointment->id
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_pickup');
            $json->has('payment_pickup.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test view payment pickup
     *
     * @return void
     */
    public function test_view_payment_pickup()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->createOneQuietly();

        $paymentPickup = PaymentPickup::factory()->for($appointment)->for($company)->create();


        $response = $this->getJson('/api/dashboard/companies/payment_pickups/view?id=' . $paymentPickup->id);


        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_pickup');
            $json->has('payment_pickup.id');
        });
    }

    /**
     * Test delete payment pickup
     *
     * @return void
     */
    public function test_delete_payment_pickup()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->createOneQuietly();

        $paymentPickup = PaymentPickup::factory()->for($appointment)->for($company)->create();

        $response = $this->deleteJson('/api/dashboard/companies/payment_pickups/delete', [
            'id' => $paymentPickup->id
        ]);

        $this->assertDatabaseHas((new PaymentPickup())->getTable(), [
            'id' => $paymentPickup->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test restore payment pickup
     *
     * @return void
     */
    public function test_restore_payment_pickup()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->createOneQuietly();

        $paymentPickup = PaymentPickup::factory()->for($appointment)->for($company)->create();

        $paymentPickup->delete();

        $response = $this->patchJson('/api/dashboard/companies/payment_pickups/restore', [
            'id' => $paymentPickup->id
        ]);

        $this->assertDatabaseHas((new PaymentPickup())->getTable(), [
            'id' => $paymentPickup->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test permanent delete payment pickup
     *
     * @return void
     */
    public function test_permanent_delete_payment_pickup()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->createOneQuietly();

        $paymentPickup = PaymentPickup::factory()->for($appointment)->for($company)->create();

        $response = $this->deleteJson('/api/dashboard/companies/payment_pickups/delete', [
            'id' => $paymentPickup->id,
            'force' => true
        ]);

        $this->assertDatabaseMissing((new PaymentPickup())->getTable(), [
            'id' => $paymentPickup->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }
}
