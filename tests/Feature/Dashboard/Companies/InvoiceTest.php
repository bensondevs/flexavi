<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Company, Owner };

use App\Enums\PaymentTerm\PaymentTermStatus;

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices';
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/overdue';
        $response = $this->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/store';
        $customer = $company->customers()->inRandomOrder()->first();
        $response = $this->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/update';
        $invoice = $company->invoices()->inRandomOrder()->first();
        $response = $this->patch($url, [
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
        /*do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
        } while (! $user = $owner->user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/invoices/send';
        $invoice = $company->invoices()->inRandomOrder()->first();
        $response = $this->post($url, [
            'invoice_id' => $invoice->id,
            'destination_email' => 'test123@gmail.com',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });*/
    }

    /**
     * A print invoice test.
     *
     * @return void
     */
    public function test_print_invoice()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/print';
        $invoice = $company->invoices()->inRandomOrder()->first();
        $response = $this->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/print_draft';
        $invoice = $company->invoices()->inRandomOrder()->first();
        $response = $this->post($url, [
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
        /*do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
        } while (! $user = $owner->user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/invoices/send_reminder';
        $invoice = $company->invoices()->inRandomOrder()->first();
        $response = $this->post($url, [
            'invoice_id' => $invoice->id,
            'destination_email' => 'destination@email.com',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });*/
    }

    /**
     * A change status invoice test.
     *
     * @return void
     */
    public function test_change_invoice_status()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/change_status';
        $invoice = $company->invoices()->inRandomOrder()->first();
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/mark_as_paid';
        $invoice = $company->invoices()->inRandomOrder()->first();
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
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/delete';
        $invoice = $company->invoices()->inRandomOrder()->first();
        $response = $this->delete($url, [
            'invoice_id' => $invoice->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A view all invoice items test.
     *
     * @return void
     */
    public function test_view_all_invoice_items()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $invoice = $company->invoices()->inRandomOrder()->first();
        $url = '/api/dashboard/companies/invoices/items?invoice_id=' . $invoice->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invoice_items');
        });
    }

    /**
     * A store invoice item test.
     *
     * @return void
     */
    public function test_store_invoice_item()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $invoice = $company->invoices()
            ->where('status', 1)
            ->inRandomOrder()
            ->first() ?: Invoice::factory()->create(['company_id' => $company->id, 'status' => 1]);
        $url = '/api/dashboard/companies/invoices/items/store';
        $response = $this->post($url, [
            'invoice_id' => $invoice->id,
            'item_name' => 'Example name',
            'description' => 'Description example',
            'quantity' => 10,
            'quantity_unit' => 'meters',
            'amount' => 90,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success'); 
        });
    }

    /**
     * An update invoice item test.
     *
     * @return void
     */
    public function test_update_invoice_item()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $invoiceItem = $company
            ->invoiceItems()
            ->whereHas('invoice', function ($invoice) {
                $invoice->where('status', 1);
            })->inRandomOrder()->first();
        $url = '/api/dashboard/companies/invoices/items/update';
        $response = $this->patch($url, [
            'invoice_item_id' => $invoiceItem->id,
            'item_name' => 'Item name example',
            'description' => 'Example item name',
            'quantity' => 10,
            'quantity_unit' => 'meter',
            'amount' => 87,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete invoice item test.
     *
     * @return void
     */
    public function test_delete_invoice_item()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $invoiceItem = $company->invoiceItems()
            ->whereHas('invoice', function ($invoice) {
                $invoice->where('status', 1);
            })->inRandomOrder()->first();
        $url = '/api/dashboard/companies/invoices/items/delete';
        $response = $this->delete($url, [
            'invoice_item_id' => $invoiceItem->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A view all invoice payment terms test.
     *
     * @return void
     */
    public function test_view_all_invoice_payment_terms()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $invoice = $company->invoices()->inRandomOrder()->first();
        $url = '/api/dashboard/companies/invoices/payment_terms?invoice_id=' . $invoice->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_terms');
        });
    }

    /**
     * A store invoice payment term test.
     *
     * @return void
     */
    public function test_store_invoice_payment_term()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $invoice = $company->invoices()
            ->inRandomOrder()
            ->first();
        $url = '/api/dashboard/companies/invoices/payment_terms/store';
        $response = $this->post($url, [
            'invoice_id' => $invoice->id,
            'term_name' => 'Example term name',
            'amount' => 1,
            'due_date' => now()->addDays(10),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A update invoice payment term test.
     *
     * @return void
     */
    public function test_update_invoice_payment_term()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/payment_terms/update';
        $term = $company->paymentTerms()
            ->whereHas('invoice', function ($invoice) {
                $invoice->where('total_in_terms', '<', 1000);
            })->where('status', PaymentTermStatus::Unpaid)
            ->inRandomOrder()
            ->first();
        $response = $this->patch($url, [
            'payment_term_id' => $term->id,
            'term_name' => 'Term name example',
            'amount' => $term->invoice->total_out_terms,
            'due_date' => now()->addDays(3),
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A mark invoice payment term as paid test.
     *
     * @return void
     */
    public function test_mark_invoice_payment_term_as_paid()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/payment_terms/mark_as_paid';
        $term = $company->paymentTerms()
            ->where('status', PaymentTermStatus::Unpaid)
            ->inRandomOrder()
            ->first();
        $response = $this->post($url, [
            'payment_term_id' => $term->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A cancel invoice payment term paid status test.
     *
     * @return void
     */
    public function test_cancel_invoice_payment_term_paid_status()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/payment_terms/cancel_paid_status';
        $term = $company->paymentTerms()
            ->where('status', PaymentTermStatus::Paid)
            ->inRandomOrder()
            ->first();
        $response = $this->post($url, [
            'payment_term_id' => $term->id,
            'reason' => 'Reason example',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A forward invoice payment term to debt collector test.
     *
     * @return void
     */
    public function test_forward_payment_term_to_debt_collector()
    {
        //
    }

    /**
     * A delete invoice payment term.
     *
     * @return void
     */
    public function test_delete_invoice_payment_term()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/invoices/payment_terms/delete';
        $term = $company->paymentTerms()
            ->where('status', PaymentTermStatus::Unpaid)
            ->inRandomOrder()
            ->first();
        $response = $this->delete($url, [
            'payment_term_id' => $term->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
