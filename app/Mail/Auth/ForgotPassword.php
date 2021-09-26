<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('mails.auths.forgot')
            ->with([
                'user' => $this->user,
                'token' => $this->token,
            ]);
    }
}
