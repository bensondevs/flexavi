<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Company, Owner, Invoice, Customer, InvoiceItem };

class InvoiceItemTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A view all invoice items test.
     *
     * @return void
     */
    public function test_view_all_invoice_items()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $invoice = Invoice::factory()
            ->for($company)
            ->has(InvoiceItem::factory()->count(rand(1, 10)), 'items')
            ->create();
        $url = '/api/dashboard/companies/invoices/items?invoice_id=' . $invoice->id;
        $response = $this->json('GET', $url);

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
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->created()
            ->create();
        $url = '/api/dashboard/companies/invoices/items/store';
        $response = $this->json('POST', $url, [
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
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $invoiceItem = InvoiceItem::factory()
            ->for($company)
            ->for(Invoice::factory()->for($company)->created()->create())
            ->create();
        $url = '/api/dashboard/companies/invoices/items/update';
        $response = $this->json('PATCH', $url, [
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
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $invoiceItem = InvoiceItem::factory()
            ->for($company)
            ->for(Invoice::factory()->for($company)->created()->create())
            ->create();
        $url = '/api/dashboard/companies/invoices/items/delete';
        $response = $this->json('DELETE', $url, [
            'invoice_item_id' => $invoiceItem->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
