<?php

namespace Tests\Unit\Services\Notification\NotificationService;

use App\Enums\Locale;
use App\Models\Notification\Notification;
use App\Services\Notification\NotificationService;

/**
 * @see NotificationService::generateFormattedContents()
 *      To the tested service class method.
 */
class GenerateFormattedContentsTest extends NotificationServiceTest
{
    /**
     * Ensure the english content is generated.
     *
     * @test
     * @return void
     */
    public function it_generates_english_content(): void
    {
        $notification = Notification::factory()->createQuietly();
        $this->notificationService()->generateFormattedContents($notification);

        $this->assertDatabaseHas('notification_formatted_contents', [
            'notification_id' => $notification->id,
            'locale' => Locale::English,
        ]);
    }

    /**
     * Ensure the dutch content is generated.
     *
     * @test
     * @return void
     */
    public function it_generates_dutch_content(): void
    {
        $notification = Notification::factory()->createQuietly();
        $this->notificationService()->generateFormattedContents($notification);

        $this->assertDatabaseHas('notification_formatted_contents', [
            'notification_id' => $notification->id,
            'locale' => Locale::Dutch,
        ]);
    }
}
