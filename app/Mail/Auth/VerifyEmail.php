<?php

namespace App\Mail\Auth;

use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verification = $this->user->createEmailVerification();
        $verificationCode = $verification->encrypted_code;

        $data = ['code' => $verificationCode];
        return $this->view('mails.auths.verify')->with($data);
    }
}
