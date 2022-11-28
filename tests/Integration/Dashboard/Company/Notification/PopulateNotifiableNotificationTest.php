<?php

namespace Tests\Integration\Dashboard\Company\Notification;

use App\Enums\Notification\NotificationPopulateType;
use App\Enums\Notification\NotificationType;
use App\Models\User\User;
use Carbon\Carbon;
use Database\Factories\NotificationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::notifiableNotifications()
 *      to the tested controller
 */
class PopulateNotifiableNotificationTest extends TestCase
{
    use WithFaker;

    /**
     * Base API URL of the module.
     *
     * @const
     */
    public const BASE_API_URL = '/api/dashboard/companies/notifications/of_notifiable';

    /**
     * Test populate notifiable notifications where types
     *
     * @return void
     */
    public function test_populate_notifiable_notifications_where_types(): void
    {
        $user = User::factory()->owner()->create();
        $user->load(['owner.company']);
        $this->actingAs($user);

        // create a single notification with type invoice
        $invoiceNotification = NotificationFactory::new()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->type(NotificationType::Invoice)
            ->create();

        // create a single notification with type quotation
        $quotationNotification = NotificationFactory::new()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->type(NotificationType::Quotation)
            ->create();

        // create a single notification with type Customer
        $customerNotification = NotificationFactory::new()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->type(NotificationType::Customer)
            ->create();

        $response = $this->getJson(
            self::BASE_API_URL .
            '?types=' . NotificationType::Quotation . ',' . NotificationType::Invoice // "?types=N,N,N,N"
        )->assertStatus(200);

        $content = json_decode($response->getContent());
        $data = (array)($content->notifications->data);

        $notificationDates = array_keys($data);

        foreach ($notificationDates as $notificationDate) {
            foreach ($data[$notificationDate] as $notification) {
                $this->assertContains($notification->type, [NotificationType::Quotation, NotificationType::Invoice]);
            }
        }
    }

    /**
     * Test populate last 3 days notifiable notifications
     *
     * @return void
     */
    public function test_populate_last_3_days_notifiable_notifications(): void
    {
        $user = User::factory()->owner()->create();
        $user->load(['owner.company']);
        $this->actingAs($user);

        $last40daysNotification = NotificationFactory::new()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->type(NotificationType::Invoice)
            ->create(['created_at' => now()->copy()->subDays(40)]);

        $last1DaysNotification = NotificationFactory::new()
            ->for($user->owner->company)
            ->for($user, 'notifier')
            ->type(NotificationType::Quotation)
            ->create(['created_at' => now()->copy()->subDay()]);

        $response = $this->getJson(
            self::BASE_API_URL .
            '?time=' . NotificationPopulateType::Last3Days
        )->assertStatus(200);


        $content = json_decode($response->getContent());
        $data = (array)($content->notifications->data);

        $notificationDates = array_keys($data);

        foreach ($notificationDates as $notificationDate) {
            foreach ($data[$notificationDate] as $notification) {
                $this->assertTrue( // ensure that notification's created_at is last 3 days
                    Carbon::parse($notification->created_at)->greaterThan(now()->subDays(3))
                );
            }
        }
    }

    /**
     * Test populate notifiable notifications by search keyword
     *
     * @return void
     */
    public function test_populate_notifiable_notifications_by_search_keyword(): void
    {
        $user = User::factory()->owner()->create();
        $user->load(['owner.company']);
        $this->actingAs($user);

        $keyword = "Hello world";

        // create three notifications that matched with search keyword
        for ($i = 1; $i <= 3; $i++) {
            $notification = NotificationFactory::new()
                ->for($user->owner->company)
                ->for($user, 'notifier')
                ->create();

            $data = [];
            switch ($i) {
                case 1:
                    $data['title'] = randomString() . " $keyword";
                    break;
                case 2:
                    $data['message'] = randomString() . " $keyword";
                    break;
                case 3:
                    $data['body'] = randomString() . " $keyword";
                    break;
            }
            $notification->formattedContent()->update($data);
        }

        // create one notification that not matched with search keyword
        (function () use ($user) {
            $notification = NotificationFactory::new()
                ->for($user->owner->company)
                ->for($user, 'notifier')
                ->create();
            $notification->formattedContent()->update([
                'title' => 'Not Matched',
                'message' => 'Not Matched',
                'body' => 'Not Matched'
            ]);
        })();

        $response = $this->getJson(self::BASE_API_URL . "?search=$keyword")
            ->assertStatus(200);


        $content = json_decode($response->getContent());
        $data = (array)($content->notifications->data);

        $notificationDates = array_keys($data);

        foreach ($notificationDates as $notificationDate) {
            foreach ($data[$notificationDate] as $notification) {
                $keys = ['title', 'message', 'body'];

                $isMacthed = false;
                foreach ($keys as $key) {
                    if ($isMacthed) {
                        return;
                    }

                    // check if  notification's property is matched with the search keyword
                    if (
                        Str::contains($notification->{$key}, $keyword)
                    ) {
                        $isMacthed = true;
                    };
                }

                // assert that the returned notification from response matches the expected search keyword
                $this->assertTrue($isMacthed);
            }
        }
    }
}
