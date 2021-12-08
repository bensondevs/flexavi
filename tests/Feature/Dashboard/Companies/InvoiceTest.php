<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Company, Owner, Invoice, Customer };

class InvoiceTest extends TestCase
{
    use DatabaseTransactions;

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

        $url = '/api/dashboard/companies/invoices';
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

        $url = '/api/dashboard/companies/invoices/overdue';
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

        $url = '/api/dashboard/companies/invoices/store';
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

        $url = '/api/dashboard/companies/invoices/update';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
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

        $url = '/api/dashboard/companies/invoices/send';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
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

        $url = '/api/dashboard/companies/invoices/print';
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

        $url = '/api/dashboard/companies/invoices/print_draft';
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
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = '/api/dashboard/companies/invoices/send_reminder';
        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->create();
        $response = $this->json('PATCH', $url, [
            'invoice_id' => $invoice->id,
            'destination_email' => 'destination@email.com',
        ]);

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

        $url = '/api/dashboard/companies/invoices/change_status';
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

        $url = '/api/dashboard/companies/invoices/mark_as_paid';
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

        $url = '/api/dashboard/companies/invoices/delete';
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
