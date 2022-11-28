<?php

namespace App\Services\Subscription\Notification;

use App\Jobs\SendMail;
use App\Mail\Subscription\SubscriptionPaymentExpired;
use App\Mail\Subscription\SubscriptionPaymentFailed;
use App\Mail\Subscription\SubscriptionPaymentNeeded;
use App\Mail\Subscription\SubscriptionPaymentSuccessful;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\User\User;

class SubscriptionPaymentNotificationService
{
    /**
     * Notify the user that model by condition
     *
     * @param SubscriptionPayment $subscriptionPayment
     */
    public function notify(SubscriptionPayment $subscriptionPayment): void
    {
        if (!$subscriptionPayment->wasChanged('status')) {
            return;
        }

        switch (true) {
            case $subscriptionPayment->isWaiting():
                $this->paymentNeeded($subscriptionPayment);
                break;
            case $subscriptionPayment->isSettled():
                $this->paymentSuccess($subscriptionPayment);
                break;
            case $subscriptionPayment->isFailed():
                $this->paymentFailed($subscriptionPayment);
                break;
            case $subscriptionPayment->isExpired():
                $this->paymentExpired($subscriptionPayment);
                break;
        }
    }

    /**
     * Notify the user that payment needed
     *
     * @param SubscriptionPayment $subscriptionPayment
     * @return void
     */
    public function paymentNeeded(SubscriptionPayment $subscriptionPayment): void
    {
        $this->send(SubscriptionPaymentNeeded::class, $subscriptionPayment->subscription);
    }

    /**
     * Dispatch a job to send a mail to all owners of the company.
     *
     * @param string $mailable
     * @param Subscription $subscription
     * @return void
     */
    public function send(string $mailable, Subscription $subscription): void
    {
        $subscription->refresh()->load(['company.owners']);
        $company = $subscription->company;
        if (!$company) {
            return;
        }
        if (!$owners = $company->owners) {
            return;
        }

        $owners->each(function ($owner) use ($mailable, $subscription) {
            $mailable = new $mailable($subscription, $owner);
            $user = $owner->user ?: User::find($owner->user_id);
            $job = new SendMail($mailable, $user->email);
            dispatch($job);
        });
    }

    /**
     * Notify the user that payment success
     *
     * @param SubscriptionPayment $subscriptionPayment
     * @return void
     */
    public function paymentSuccess(SubscriptionPayment $subscriptionPayment): void
    {
        $this->send(SubscriptionPaymentSuccessful::class, $subscriptionPayment->subscription);
    }

    /**
     * Notify the user that payment failed
     *
     * @param SubscriptionPayment $subscriptionPayment
     * @return void
     */
    public function paymentFailed(SubscriptionPayment $subscriptionPayment): void
    {
        $this->send(SubscriptionPaymentFailed::class, $subscriptionPayment->subscription);
    }

    /**
     * Notify the user that payment expired
     *
     * @param SubscriptionPayment $subscriptionPayment
     * @return void
     */
    public function paymentExpired(SubscriptionPayment $subscriptionPayment): void
    {
        $this->send(SubscriptionPaymentExpired::class, $subscriptionPayment->subscription);
    }
}
