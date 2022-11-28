<?php

namespace App\Observers;

use App\Models\Notification\NotificationFormattedContent;

class NotificationFormattedContentObserver
{
    /**
     * Handle the NotificationFormattedContent "creating" event.
     *
     * @param NotificationFormattedContent $content
     * @return void
     */
    public function creating(NotificationFormattedContent $content): void
    {
        $content->id = generateUuid();
    }

    /**
     * Handle the NotificationFormattedContent "created" event.
     *
     * @param NotificationFormattedContent $content
     * @return void
     */
    public function created(NotificationFormattedContent $content): void
    {
        //
    }

    /**
     * Handle the NotificationFormattedContent "updated" event.
     *
     * @param NotificationFormattedContent $content
     * @return void
     */
    public function updated(NotificationFormattedContent $content): void
    {
        //
    }

    /**
     * Handle the NotificationFormattedContent "deleted" event.
     *
     * @param NotificationFormattedContent $content
     * @return void
     */
    public function deleted(NotificationFormattedContent $content): void
    {
        //
    }

    /**
     * Handle the NotificationFormattedContent "restored" event.
     *
     * @param NotificationFormattedContent $content
     * @return void
     */
    public function restored(NotificationFormattedContent $content): void
    {
        //
    }

    /**
     * Handle the NotificationFormattedContent "force deleted" event.
     *
     * @param NotificationFormattedContent $content
     * @return void
     */
    public function forceDeleted(NotificationFormattedContent $content): void
    {
        //
    }
}
