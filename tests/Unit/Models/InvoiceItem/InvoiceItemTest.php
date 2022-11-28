<?php

namespace Tests\Unit\Models\InvoiceItem;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\WorkService\WorkService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * @see \App\Models\Invoice\InvoiceItem
 *      To see model class
 */
class InvoiceItemTest extends TestCase
{
    /**
     * To see if model exists
     * @test
     * @return void
     */
    public function model_exists(): void
    {
        $this->assertTrue(class_exists(\App\Models\Invoice\InvoiceItem::class));
    }

    /**
     * Testing it invoice item has invoice relationship
     *
     * @test
     * @return void
     * @see \App\Models\Invoice\InvoiceItem::invoice()
     *      To see model relationship
     */
    public function it_invoice_item_has_invoice_relationship(): void
    {
        $invoice = Invoice::factory()->create();
        $item = InvoiceItem::factory()->for($invoice)->create();
        $this->assertInstanceOf(Invoice::class, $item->invoice);
        $this->assertInstanceOf(BelongsTo::class, $item->invoice());
        $this->assertEquals($item->invoice->load('company'), $invoice->fresh()->load('company'));
    }

    /**
     * Testing it invoice item has work service relationship
     *
     * @test
     * @return void
     * @see \App\Models\Invoice\InvoiceItem::workService()
     *      To see model relationship
     */
    public function it_invoice_item_has_work_service_relationship(): void
    {
        $workService = WorkService::factory()->create();
        $item = InvoiceItem::factory()->for($workService)->create();
        $this->assertInstanceOf(WorkService::class, $item->workService);
        $this->assertInstanceOf(BelongsTo::class, $item->workService());
        $this->assertEquals($item->workService, $workService->fresh());
    }

}
