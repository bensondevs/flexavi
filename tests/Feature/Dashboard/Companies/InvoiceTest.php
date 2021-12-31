<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Company, Owner, Invoice, Customer };
use App\Jobs\Invoice\{
    SendInvoiceFirstReminder,
    SendInvoiceSecondReminder,
    SendInvoiceThirdReminder
};

class InvoiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Module test base url
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/invoices';

    /**
     * A view all invoices test.
     *
     * @return void
     */
    public function test_view_all_invoices()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invoices');
        });
    }

    /**
     * A view all overdue invoices test.
     *
     * @return void
     */
    public function test_view_all_overdue_invoices()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/overdue';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invoices'); 
        });
    }

    /**
     * A store invoice test.
     *
     * @return void
     */
    public function test_store_invoice()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/store';
        $customer = Customer::factory()->for($company)->create();
        $response = $this->json('POST', $url, [
            'customer_id' => $customer->id,
            'payment_method' => 2,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * An update invoice test.
     *
     * @return void
     */
    public function test_update_invoice()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/update';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->created()
            ->create();
        $response = $this->json('PATCH', $url, [
            'invoice_id' => $invoice->id,
            'payment_method' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A send invoice test.
     *
     * @return void
     */
    public function test_send_invoice()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/send';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->created()
            ->create();
        $response = $this->json('POST', $url, [
            'invoice_id' => $invoice->id,
            'destination_email' => 'test123@gmail.com',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A print invoice test.
     *
     * @return void
     */
    public function test_print_invoice()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/print';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->create();
        $response = $this->json('POST', $url, [
            'invoice_id' => $invoice->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('invoice');
        });
    }

    /**
     * A print draft invoice test.
     *
     * @return void
     */
    public function test_print_invoice_draft()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/print_draft';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->create();
        $response = $this->json('POST', $url, [
            'invoice_id' => $invoice->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('invoice');
        });
    }

    /**
     * A send reminder invoice test.
     *
     * @return void
     */
    public function test_send_invoice_reminder()
    {
        Queue::fake();

        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/send_reminder';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->paymentOverdue()
            ->create();
        $response = $this->json('POST', $url, [
            'invoice_id' => $invoice->id,
            'destination_email' => 'destination@email.com',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(SendInvoiceFirstReminder::class);

    }

    /**
     * A send first invoice reminder test
     * 
     * @return void
     */
    public function test_send_first_invoice_reminder()
    {
        Queue::fake();

        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/send_first_reminder';
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->paymentOverdue()
            ->create();
        $response = $this->json('POST', $url, [
            'invoice_id' => $invoice->id,
            'destination_email' => 'destination@email.com',
            'custom_message' => 'Hello please pay your debt.',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(SendInvoiceFirstReminder::class);
    }

    /**
     * A send second invoice reminder test
     * 
     * @return void
     */
    public function test_send_second_invoice_reminder()
    {
        Queue::fake();

        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;

        $url = $this->baseUrl . '/send_second_reminder';
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->firstReminderOverdue() // Has passed first reminder overdue
            ->create();

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
        
        Queue::assertPushed(SendInvoiceSecondReminder::class);
    }

    /**
     * A send third invoice reminder test
     * 
     * @return void
     */
    public function test_send_third_invoice_reminder()
    {
        Queue::fake();

        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;

        $url = $this->baseUrl . '/send_third_reminder';
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->secondReminderOverdue() // Has passed second reminder overdue
            ->create();

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
        
        Queue::assertPushed(SendInvoiceThirdReminder::class);
    }

    /**
     * A forward invoice to debt collector test
     * 
     * @return void
     */
    public function test_forward_to_debt_collector()
    {
        // Event::fake();

        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;

        $url = $this->baseUrl . 'forward_to_debt_collector';
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->thirdReminderOverdue() // Has passed the third reminder overdue
            ->create();

        // Event::assertDispatched(ForwardToDebtCollector::class);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A change status invoice test.
     *
     * @return void
     */
    public function test_change_invoice_status()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/change_status';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->create();
        $response = $this->patch($url, [
            'invoice_id' => $invoice->id,
            'status' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A mark invoice as paid test.
     *
     * @return void
     */
    public function test_mark_invoice_as_paid()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/mark_as_paid';
        $invoice = Invoice::factory()->for($company)->sent()->create();
        $response = $this->post($url, [
            'invoice_id' => $invoice->id,
            'is_via_debt_collector' => false,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete invoice test.
     *
     * @return void
     */
    public function test_delete_invoice()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/delete';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->create();
        $response = $this->json('DELETE', $url, [
            'invoice_id' => $invoice->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
