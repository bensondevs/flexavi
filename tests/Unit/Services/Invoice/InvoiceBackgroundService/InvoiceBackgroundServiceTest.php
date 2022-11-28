<?php

namespace Tests\Unit\Services\Invoice\InvoiceBackgroundService;

use App\Services\Invoice\InvoiceBackgroundService;
use Tests\TestCase;

/**
 * @see \App\Services\Invoice\InvoiceBackgroundService
 *      To see class under test
 */
class InvoiceBackgroundServiceTest extends TestCase
{
    /**
     * Service instance container property.
     *
     * @var InvoiceBackgroundService|null
     */
    private ?InvoiceBackgroundService $invoiceBackgroundService = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            InvoiceBackgroundService::class,
            $this->service()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return InvoiceBackgroundService
     */
    protected function service(bool $force = false): InvoiceBackgroundService
    {
        if ($this->invoiceBackgroundService instanceof InvoiceBackgroundService and !$force) {
            return $this->invoiceBackgroundService;
        }

        return $this->invoiceBackgroundService = app(InvoiceBackgroundService::class);
    }
}
