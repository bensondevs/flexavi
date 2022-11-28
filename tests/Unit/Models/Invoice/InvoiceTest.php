<?php

namespace Tests\Unit\Models\Invoice;

use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Invoice\InvoiceLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * @see \App\Models\Invoice\Invoice
 *      To see model class
 */
class InvoiceTest extends TestCase
{
    /**
     * To see if model exists
     * @test
     * @return void
     */
    public function model_exists(): void
    {
        $this->assertTrue(class_exists(\App\Models\Invoice\Invoice::class));
    }

    /**
     * Testing it invoice has company relationship
     *
     * @test
     * @return void
     * @see \App\Models\Invoice\Invoice::company()
     *      To see model relationship
     */
    public function it_invoice_has_company_relationship(): void
    {
        $company = Company::factory()->create();
        $invoice = Invoice::factory()->for($company)->create();
        $this->assertInstanceOf(Company::class, $invoice->company);
        $this->assertInstanceOf(BelongsTo::class, $invoice->company());
        $this->assertEquals($invoice->company, $company->fresh());
    }

    /**
     * Testing it invoice has customer relationship
     *
     * @test
     * @return void
     * @see \App\Models\Invoice\Invoice::customer()
     *      To see model relationship
     */
    public function it_invoice_has_customer_relationship(): void
    {
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->for($customer)->create();
        $this->assertInstanceOf(Customer::class, $invoice->customer);
        $this->assertInstanceOf(BelongsTo::class, $invoice->customer());
        $this->assertEquals($invoice->customer, $customer->fresh());
    }

    /**
     * Testing it invoice has invoice logs relationship
     *
     * @test
     * @return void
     * @see \App\Models\Invoice\Invoice::logs()
     *      To see model relationship
     */
    public function it_invoice_has_invoice_logs_relationship(): void
    {
        $invoice = Invoice::factory()->create();
        $invoiceLogs = InvoiceLog::factory()->for($invoice)->count(rand(1, 4))->create();
        $this->assertInstanceOf(HasMany::class, $invoice->logs());
    }

    /**
     * Testing it invoice has invoice items relationship
     *
     * @test
     * @return void
     * @see \App\Models\Invoice\Invoice::items()
     *      To see model relationship
     */
    public function it_invoice_has_invoice_items_relationship(): void
    {
        $invoice = Invoice::factory()->create();
        $items = InvoiceItem::factory()->for($invoice)->create();
        $invoice = $invoice->fresh()->load('items');
        $this->assertInstanceOf(HasMany::class, $invoice->items());
    }
}
