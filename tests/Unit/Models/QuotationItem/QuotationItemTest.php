<?php

namespace Tests\Unit\Models\QuotationItem;

use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationItem;
use App\Models\WorkService\WorkService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * @see \App\Models\Quotation\QuotationItem
 *      To see model class
 */
class QuotationItemTest extends TestCase
{
    /**
     * To see if model exists
     * @test
     * @return void
     */
    public function model_exists(): void
    {
        $this->assertTrue(class_exists(\App\Models\Quotation\QuotationItem::class));
    }

    /**
     * Testing it quotation item has quotation relationship
     *
     * @test
     * @return void
     * @see \App\Models\Quotation\QuotationItem::quotation()
     *      To see model relationship
     */
    public function it_quotation_item_has_quotation_relationship(): void
    {
        $quotation = Quotation::factory()->create();
        $item = QuotationItem::factory()->for($quotation)->create();
        $this->assertInstanceOf(Quotation::class, $item->quotation);
        $this->assertInstanceOf(BelongsTo::class, $item->quotation());
        $this->assertEquals($item->quotation->load('company'), $quotation->fresh()->load('company'));
    }

    /**
     * Testing it quotation item has work service relationship
     *
     * @test
     * @return void
     * @see \App\Models\Quotation\QuotationItem::workService()
     *      To see model relationship
     */
    public function it_quotation_item_has_work_service_relationship(): void
    {
        $workService = WorkService::factory()->create();
        $item = QuotationItem::factory()->for($workService)->create();
        $this->assertInstanceOf(WorkService::class, $item->workService);
        $this->assertInstanceOf(BelongsTo::class, $item->workService());
        $this->assertEquals($item->workService, $workService->fresh());
    }
}
