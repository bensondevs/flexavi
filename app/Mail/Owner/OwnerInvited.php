<?php

namespace App\Mail\Owner;

use App\Models\Owner\OwnerInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OwnerInvited extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * OwnerInvitation object
     *
     * @var OwnerInvitation
     */
    private OwnerInvitation $ownerInvitation;

    /**
     * Create a new message instance.
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function __construct(OwnerInvitation $ownerInvitation)
    {
        $this->ownerInvitation = $ownerInvitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.invitations.owner', [
            'invitation' => $this->ownerInvitation,
        ]);
    }
}
