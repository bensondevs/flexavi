<?php

namespace Database\Seeders;

use App\Enums\Notification\NotificationType;
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Models\Employee\Employee;
use App\Models\Notification\Notification;
use App\Models\Notification\NotificationFormattedContent;
use App\Models\Owner\Owner;
use App\Models\User\User;
use App\Models\WorkService\WorkService;
use Faker\Factory;
use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Notification::whereNotNull('id')->delete();
        $faker = Factory::create();
        $rawNotifications = [];

        $moduleNames = $this->notificationNames();
        foreach (Owner::with(['company', 'user'])->get() as $owner) {
            if ($owner->company && $owner->user) {
                foreach ($moduleNames as $moduleName => $actionNames) {
                    foreach ($actionNames as $actionName => $actionNameValues) {
                        $actor = User::inRandomOrder()->first();
                        $object = $this->getObject($moduleName);
                        $rawNotifications[] = [
                            'id' => generateUuid(),
                            'company_id' => $owner->company->id,
                            'notification_name' => $moduleName . "." . $actionName,
                            'type' => NotificationType::getRandomValue(),
                            'actor_id' => $actor->id,
                            'actor_type' => get_class($actor),
                            'notifier_type' => User::class,
                            'notifier_id' => $owner->user->id,
                            'object_type' => $object['object_type'],
                            'object_id' => $object['object_id'],
                            'extras' => json_encode($object['extras']),
                            'created_at' => now()->subDays(rand(1, 30)),
                            'updated_at' => now()->subDays(rand(1, 30)),
                        ];
                    }
                }
            }
        }
        foreach (array_chunk($rawNotifications, 100) as $rawNotification) {
            Notification::insert($rawNotification);
        }

        $contents = [];
        foreach (Notification::all() as $notification) {
            $contents[] = [
                'id' => generateUuid(),
                'notification_id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'body' => $notification->body,
                'locale' => 'en',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
            $contents[] = [
                'id' => generateUuid(),
                'notification_id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'body' => $notification->body,
                'locale' => 'nl',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
        }

        foreach (array_chunk($contents, 200) as $content) {
            NotificationFormattedContent::insert($content);
        }
    }

    /**
     * Get all notification names
     *
     * @return array
     */
    public function notificationNames(): array
    {
        return \Lang::get('notifications');
    }

    /**
     * Get object
     *
     * @param $notificationType
     * @return array
     */
    public function getObject($notificationType): array
    {
        $objectTypes = [
            'customer' => Customer::class,
            'owner' => Owner::class,
            'employee' => Employee::class,
            'work_service' => WorkService::class,
            'company' => Company::class,
        ];

        $model = (new $objectTypes[$notificationType]())->inRandomOrder()->first();
        return [
            'object_type' => get_class($model),
            'object_id' => $model->id,
            'extras' => $model->toArray()
        ];
    }
}
