<?php

namespace App\Services\Subscription\Notification;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionFreeTrial;
use App\Mail\Subscription\SubscriptionLastDayLeftExpired;
use App\Mail\Subscription\SubscriptionStarted;
use App\Mail\Subscription\SubscriptionThreeDaysLeftExpired;
use App\Models\Subscription\Subscription;
use App\Models\User\User;

class SubscriptionNotificationService
{
    /**
     * Notify the user subscription will expire in three days
     *
     * @param Subscription $subscription
     */
    public function threeDaysReminder(Subscription $subscription): void
    {
        $this->send(SubscriptionThreeDaysLeftExpired::class, $subscription);
    }

    /**
     * Dispatch a job to send a mail to all owners of the company.
     *
     * @param string $mailable
     * @param $subscription
     * @return void
     */
    public function send(string $mailable, $subscription): void
    {
        $subscription->company->owners->each(function ($owner) use ($mailable, $subscription) {
            $mailable = new $mailable($subscription, $owner);
            $user = $owner->user ?: User::find($owner->user_id);
            $job = new SendMail($mailable, $user->email);
            dispatch($job);
        });
    }

    /**
     * Notify the user subscription will expire in one day
     *
     * @param Subscription $subscription
     */
    public function lastDayReminder(Subscription $subscription): void
    {
        $this->send(SubscriptionLastDayLeftExpired::class, $subscription);
    }

    /**
     * Notify the user on trial subscription started
     *
     * @param Subscription $subscription
     */
    public function trialStarted(Subscription $subscription): void
    {
        $this->send(SubscriptionFreeTrial::class, $subscription);
    }

    /**
     * Notify the user that subscription started
     *
     * @param Subscription $subscription
     * @return void
     */
    public function started(Subscription $subscription): void
    {
        $this->send(SubscriptionStarted::class, $subscription);
    }
}
