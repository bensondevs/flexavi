<?php

namespace App\Mail;

use App\Models\Invitation\RegisterInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FakeRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    private RegisterInvitation $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(RegisterInvitation $registerInvitation)
    {
        $this->invitation = $registerInvitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $invitation = $this->invitation;
        return $this->view('mails.developers.fake-registration')
            ->with(['invitation' => $invitation]);
    }
}
