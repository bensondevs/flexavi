<?php

namespace App\Mail\Subscription;

use App\Models\Owner\Owner;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionStarted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Subscription model container
     *
     * @var Subscription
     */
    private Subscription $subscription;

    /**
     * Owner model container
     *
     * @var Owner
     */
    private Owner $owner;

    /**
     * User model container
     *
     * @var User
     */
    private User $user;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, Owner $owner)
    {
        $this->subscription = $subscription->load('subscriptionPlanPeriod.subscriptionPlan');
        $this->owner = $owner;
        $this->user = $owner->user ?: User::find($owner->user_id);
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Started',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.subscriptions.started',
            with: [
                'subscription' => $this->subscription,
                'owner' => $this->owner,
                'user' => $this->user,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
