<?php

namespace Tests\Unit\Models\Quotation;

use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationItem;
use App\Models\Quotation\QuotationLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * @see \App\Models\Quotation\Quotation
 *      To see model class
 */
class QuotationTest extends TestCase
{
    /**
     * To see if model exists
     * @test
     * @return void
     */
    public function model_exists(): void
    {
        $this->assertTrue(class_exists(\App\Models\Quotation\Quotation::class));
    }

    /**
     * Testing it quotation has company relationship
     *
     * @test
     * @return void
     * @see \App\Models\Quotation\Quotation::company()
     *      To see model relationship
     */
    public function it_quotation_has_company_relationship(): void
    {
        $company = Company::factory()->create();
        $quotation = Quotation::factory()->for($company)->create();
        $this->assertInstanceOf(Company::class, $quotation->company);
        $this->assertInstanceOf(BelongsTo::class, $quotation->company());
        $this->assertEquals($quotation->company, $company->fresh());
    }

    /**
     * Testing it quotation has customer relationship
     *
     * @test
     * @return void
     * @see \App\Models\Quotation\Quotation::customer()
     *      To see model relationship
     */
    public function it_quotation_has_customer_relationship(): void
    {
        $customer = Customer::factory()->create();
        $quotation = Quotation::factory()->for($customer)->create();
        $this->assertInstanceOf(Customer::class, $quotation->customer);
        $this->assertInstanceOf(BelongsTo::class, $quotation->customer());
        $this->assertEquals($quotation->customer, $customer->fresh());
    }

    /**
     * Testing it quotation has quotation logs relationship
     *
     * @test
     * @return void
     * @see \App\Models\Quotation\Quotation::logs()
     *      To see model relationship
     */
    public function it_quotation_has_quotation_logs_relationship(): void
    {
        $quotation = Quotation::factory()->create();
        $quotationLogs = QuotationLog::factory()
            ->for($quotation)
            ->count($quantity = rand(1, 4))
            ->create();
        $this->assertInstanceOf(HasMany::class, $quotation->logs());

        $createdLogRecords = QuotationLog::whereQuotationId($quotation->id)->count();
        $this->assertTrue($createdLogRecords >= $quotationLogs->count());
    }

    /**
     * Testing it quotation has quotation items relationship
     *
     * @test
     * @return void
     * @see \App\Models\Quotation\Quotation::items()
     *      To see model relationship
     */
    public function it_quotation_has_quotation_items_relationship(): void
    {
        $quotation = Quotation::factory()->create();
        $items = QuotationItem::factory()->for($quotation)->create();
        $quotation = $quotation->fresh()->load('items');
        $this->assertInstanceOf(HasMany::class, $quotation->items());
    }

}
