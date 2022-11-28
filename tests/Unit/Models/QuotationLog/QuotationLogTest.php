<?php

namespace Tests\Unit\Models\QuotationLog;

use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * @see \App\Models\Quotation\QuotationLog
 *      To see model class
 */
class QuotationLogTest extends TestCase
{
    /**
     * To see if model exists
     * @test
     * @return void
     */
    public function model_exists(): void
    {
        $this->assertTrue(class_exists(\App\Models\Quotation\QuotationLog::class));
    }

    /**
     * Testing it quotation log has quotation relationship
     *
     * @test
     * @return void
     * @see \App\Models\Quotation\QuotationLog::quotation()
     *      To see model relationship
     */
    public function it_quotation_log_has_quotation_relationship(): void
    {
        $quotation = Quotation::factory()->create();
        $log = QuotationLog::factory()->for($quotation)->create();
        $this->assertInstanceOf(Quotation::class, $log->quotation);
        $this->assertInstanceOf(BelongsTo::class, $log->quotation());
        $this->assertEquals($log->quotation->load('company'), $quotation->fresh()->load('company'));
    }
}
