<?php

namespace Tests\Feature\Dashboard\Company\Notification;

use App\Models\Notification\Notification;
use App\Models\Permission\Permission;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Notification\NotificationController
 *      To the tested controller class.
 */
class NotificationTest extends TestCase
{
    use WithFaker;

    /**
     * Base API URL of the module.
     *
     * @const
     */
    public const BASE_API_URL = '/api/dashboard/companies/notifications';

    /**
     * Test populate notifiable notifications
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::notifiableNotifications()
     *      To the tested controller method.
     */
    public function test_populate_notifiable_notifications(): void
    {
        $ownerUser = User::factory()->owner()->create();
        $ownerUser->load(['owner.company']);
        $this->actingAs($ownerUser);

        Notification::factory()
            ->for($ownerUser->owner->company)
            ->for($ownerUser, 'notifier')
            ->create();

        $response = $this->getJson(self::BASE_API_URL . '/of_notifiable');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "notifications" => [
                "data",
                "current_page",
                "first_page_url",
                "from",
                "last_page",
                "last_page_url",
                "links",
                "next_page_url",
                "path",
                "per_page",
                "prev_page_url",
                "to",
                "total",
            ],
        ]);
    }

    /**
     * Test count notifiable notifications.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::countNotifiableNotifications()
     *      To the tested controller method.
     */
    public function test_count_notifiable_notifications(): void
    {
        $user = User::factory()->owner()->create();
        $user = $user->givePermissionTo(Permission::all());
        $this->actingAs($user);

        // Generate unread notifications
        $unreadNotifications = Notification::factory()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->unread()
            ->create();

        $response = $this->getJson(self::BASE_API_URL . '/count/user');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has("notification");
        });
        $count = $response->getOriginalContent()['notification'];
        $this->assertTrue(
            $unreadNotifications->count() >= $count['unread']
        );
    }

    /**
     * Test mark all notifications that belongs to the notifiable as read.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::markAllRead()
     *      To the tested controller method.
     */
    public function test_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->owner()->create();
        $user = $user->givePermissionTo(Permission::all());
        $this->actingAs($user);

        $notifications = Notification::factory()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->unread()
            ->count(2)
            ->create();

        $response = $this->postJson(self::BASE_API_URL . '/mark_all_read');
        $response->assertStatus(201);
        $response->assertJsonStructure(['status', 'message']);

        // Ensure all notifications are read.
        $notifications->each(function (Notification $notification) {
            $notification = $notification->fresh();

            $this->assertNotNull($notification->read_at) ;
        });
    }

    /**
     * Test mark all notifications that belongs to the notifiable as unread
     *
     * @deprecated
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::markAllUnread()
     */
    public function test_mark_all_notifications_as_unread(): void
    {
        $user = User::factory()->owner()->create();
        $user = $user->givePermissionTo(Permission::all());
        $this->actingAs($user);

        $notifications = Notification::factory()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->read()
            ->count(2)
            ->create();

        $response = $this->postJson(self::BASE_API_URL . '/mark_all_unread');
        $response->assertStatus(201);
        $response->assertJsonStructure(['status', 'message']);

        // Ensure all notifications are unread.
        $notifications->each(function (Notification $notification) {
            $notification = $notification->fresh();

            $this->assertNull($notification->read_at) ;
        });
    }
}
