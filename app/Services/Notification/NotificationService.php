<?php

namespace App\Services\Notification;

use App\Enums\Locale;
use App\Models\Notification\Notification;
use App\Models\Notification\NotificationFormattedContent;
use App\Models\Owner\Owner;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @see \Tests\Unit\Services\Notification\NotificationService\NotificationServiceTest
 *      To the service class unit tester class.
 */
class NotificationService
{
    /**
     * Notification Instance
     *
     * @var Notification
     */
    private Notification $notification;

    /**
     * Instantiate notification service
     *
     * @param array $notification
     * @return void
     */
    public function __construct(array $notification)
    {
        $this->notification = new Notification($notification);
    }

    /**
     * Generate formatted contents.
     *
     * @param Notification $notification
     * @return void
     * @see \Tests\Unit\Services\Notification\NotificationService\GenerateFormattedContentsTest
     *      To the service method unit tester class.
     */
    public static function generateFormattedContents(Notification $notification): void
    {
        // Generate english locale content
        NotificationFormattedContent::create([
            'notification_id' => $notification->id,
            'locale' => Locale::English,
            'title' => self::format($notification, "notifications.$notification->notification_name.title", Locale::English),
            'message' => self::format($notification, "notifications.$notification->notification_name.message", Locale::English),
            'body' => self::format($notification, "notifications.$notification->notification_name.body", Locale::English),
        ]);

        // Generate dutch locale content
        NotificationFormattedContent::create([
            'notification_id' => $notification->id,
            'locale' => Locale::Dutch,
            'title' => self::format($notification, "notifications.$notification->notification_name.title", Locale::Dutch),
            'message' => self::format($notification, "notifications.$notification->notification_name.message", Locale::Dutch),
            'body' => self::format($notification, "notifications.$notification->notification_name.body", Locale::Dutch),
        ]);
    }

    /**
     * Format the notification service.
     *
     * @param Notification $notification
     * @param string $notificationName
     * @param string $locale
     * @return string|null
     */
    public static function format(
        Notification $notification,
        string       $notificationName,
        string       $locale = 'en',
    ): ?string {
        if ($notificationName === trans(key: $notificationName, locale: $locale)) {
            return null;
        }
        $notificationContent = trans(key: $notificationName, locale: $locale);

        $actor = $notification->actor;
        $extras = json_decode($notification->extras);
        $object = $notification->object;

        $variables = Str::matchAll("/:([A-Za-z0-9_.]+)/", $notificationContent);
        if (count($variables) === 0) {
            return $notificationContent;
        }

        $replaces = [];
        foreach ($variables->toArray() as $variable) {
            $data = ${Str::before($variable, ".")};
            $key = Str::after($variable, ".");
            if (is_null($data)) {
                return null;
            }

            $replaces[$variable] = arrayobject_accessor($data, $key);
        }
        return trans(key: $notificationName, replace: $replaces, locale: $locale);
    }

    /**
     * Format the notification title.
     *
     * @param Notification $notification
     * @return string|null
     */
    public static function formatTitle(Notification $notification): ?string
    {
        $notificationName = "notifications.$notification->notification_name.title";
        return self::format($notification, $notificationName);
    }

    /**
     * Format the notification message.
     *
     * @param Notification $notification
     * @param string $locale
     * @return string|null
     */
    public static function formatMessage(
        Notification $notification,
        string       $locale = 'en'
    ): ?string {
        $notificationName = "notifications.$notification->notification_name.message";
        return self::format($notification, $notificationName, $locale);
    }

    /**
     * Format the notification body.
     *
     * @param Notification $notification
     * @return string|null
     */
    public static function formatBody(Notification $notification): ?string
    {
        $notificationName = "notifications.$notification->notification_name.body";
        return self::format($notification, $notificationName);
    }

    /**
     * Make a new notification
     *
     * @param string $notificationName
     * @return static
     */
    public static function make(string $notificationName): static
    {
        $attributes = [
            'notification_name' => $notificationName,
        ];
        return new static($attributes);
    }

    /**
     *  Define the notification actor
     *
     * @param object $actor
     * @return static
     */
    public function by(object $actor): static
    {
        $notification = $this->getNotification();

        $roleModel =
            auth()->user()->role_model
            ?? $actor->role_model
            ?? $actor
            ?? null;

        $this->notification = $notification->fill([
            "company_id" => is_null($roleModel) ? null : $roleModel->company_id,

            "actor_type" => get_class($actor),
            "actor_id" => $actor->id,
        ]);

        return $this;
    }

    /**
     *  Get Notification Instance
     *
     * @return Notification
     */
    public function getNotification(): Notification
    {
        return $this->notification;
    }

    /**
     *  Define the Log performed on
     *
     * @param Model $object
     * @return static
     */
    public function on(Model $object): static
    {
        $notification = $this->getNotification();
        $this->notification = $notification->fill([
            "object_type" => get_class($object),
            "object_id" => $object->id,
        ]);

        return $this;
    }

    /**
     *  Add Property to Log with dot notation array
     *
     * @param array $extras
     * @return static
     */
    public function extras(array $extras = []): static
    {
        $notification = $this->getNotification();

        $notification->extras = json_encode(array_merge($notification->extras ?? [], $extras));
        $this->notification = $notification;

        return $this;
    }

    /**
     * Write notification to database
     *
     * @return Notification
     */
    public function write(): Notification
    {
        $notification = $this->getNotification();
        $notification->save();
        return $notification;
    }

    /**
     * Write notification to database
     *
     * @return void
     */
    public function writeToOwners(): void
    {
        $notification = $this->getNotification();
        $owners = Owner::query()->with('user')
            ->whereHas('user')
            ->whereCompanyId($notification->company_id)
            ->get();

        foreach ($owners as $owner) {
            $replicatedNotification = $notification->replicate();
            $replicatedNotification->notifier_type = User::class;
            $replicatedNotification->notifier_id = $owner->user->id;
            $replicatedNotification->save();
        }
    }

    /**
     * Send notification to user
     *
     * @param User $user
     * @return static
     */
    public function to(User $user): static
    {
        $notification = $this->getNotification();
        $notification->notifier_type = get_class($user);
        $notification->notifier_id = $user->id;
        $this->notification = $notification;
        return $this;
    }
}
