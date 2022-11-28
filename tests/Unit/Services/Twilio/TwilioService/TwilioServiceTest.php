<?php

namespace Services\Twilio\TwilioService;

use App\Services\Twilio\TwilioService;
use Tests\TestCase;

/**
 * @see \App\Services\Twilio\TwilioService
 *      To see service class
 */
class TwilioServiceTest extends TestCase
{
    /**
     * Repository instance container property.
     *
     * @var ?TwilioService
     */
    private ?TwilioService $service = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            TwilioService::class,
            $this->repository()
        );
    }

    /**
     * Create or get repository instance
     *
     * @param bool $force
     * @return TwilioService
     */
    protected function repository(bool $force = false): TwilioService
    {
        if ($this->service instanceof TwilioService and !$force) {
            return $this->service;
        }

        return $this->service = new TwilioService();
    }
}
