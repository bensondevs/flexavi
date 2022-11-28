<?php

namespace Tests\Unit\Services\Notification\NotificationService;

use App\Services\Notification\NotificationService;
use Tests\TestCase;

/**
 * @see \App\Services\Notification\NotificationService
 *      To see service class
 */
class NotificationServiceTest extends TestCase
{
    /**
     * Repository instance container property.
     *
     * @var ?NotificationService
     */
    private ?NotificationService $service = null;

    /**
     * Create or get repository instance
     *
     * @param bool $force
     * @return NotificationService
     */
    protected function notificationService(bool $force = false): NotificationService
    {
        if ($this->service instanceof NotificationService and !$force) {
            return $this->service;
        }

        return $this->service = new NotificationService([]);
    }

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            NotificationService::class,
            $this->notificationService(),
        );
    }
}
