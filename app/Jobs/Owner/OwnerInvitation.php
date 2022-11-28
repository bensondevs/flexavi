<?php

namespace App\Jobs\Owner;

use App\Mail\Owner\OwnerInvited;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OwnerInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    /**
     * OwnerInvitation object
     *
     * @var \App\Models\Owner\OwnerInvitation
     */
    private \App\Models\Owner\OwnerInvitation $ownerInvitation;


    /**
     * Create a new job instance.
     *
     * @param \App\Models\Owner\OwnerInvitation $ownerInvitation
     * @return void
     */
    public function __construct(\App\Models\Owner\OwnerInvitation $ownerInvitation)
    {
        $this->ownerInvitation = $ownerInvitation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail = new OwnerInvited($this->ownerInvitation);
        Mail::to($this->ownerInvitation->invited_email)->send($mail);
    }
}
