<?php

namespace App\Observers;

use App\Events\Notification\NotificationCreated;
use App\Models\Notification\Notification;
use App\Services\Notification\NotificationService;

class NotificationObserver
{
    /**
     * Handle the Notification "creating" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function creating(Notification $notification): void
    {
        $notification->id = generateUuid();
    }

    /**
     * Handle the Notification "created" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function created(Notification $notification): void
    {
        // Generate formatted contents
        NotificationService::generateFormattedContents($notification);

        if (!app()->runningUnitTests()) {
            NotificationCreated::dispatch($notification);
        }
    }

    /**
     * Handle the Notification "updated" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function updated(Notification $notification): void
    {
        //
    }

    /**
     * Handle the Notification "deleted" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function deleted(Notification $notification): void
    {
        //
    }

    /**
     * Handle the Notification "restored" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function restored(Notification $notification): void
    {
        //
    }

    /**
     * Handle the Notification "force deleted" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function forceDeleted(Notification $notification): void
    {
        //
    }
}
