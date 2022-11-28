<?php

namespace App\Jobs\Auth;

use App\Enums\Auth\ResetPasswordType;
use App\Jobs\SendMail;
use App\Mail\Auth\ForgotPassword;
use App\Models\User\PasswordReset;
use App\Models\User\User;
use App\Services\Twilio\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Exceptions\TwilioException;

class SendResetPasswordToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target user model container
     *
     * @var User
     */
    private User $user;

    /**
     * Target password reset model container
     *
     * @var PasswordReset
     */
    private PasswordReset $passwordReset;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param PasswordReset $passwordReset
     */
    public function __construct(
        User          $user,
        PasswordReset $passwordReset,
    )
    {
        $this->user = $user;
        $this->passwordReset = $passwordReset;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws TwilioException
     */
    public function handle(): void
    {
        $user = $this->user;
        $passwordReset = $this->passwordReset;

        if ($passwordReset->reset_via == ResetPasswordType::SMS) {
            $message = "Hello from flexavi. this is token for reset your account. Token : " . $passwordReset->token;
            (new TwilioService())->send($message, $user->phone);
            return;
        }

        $mailable = new ForgotPassword($user, $passwordReset->token);
        $job = new SendMail($mailable, $user->email);
        dispatch($job);
    }
}
