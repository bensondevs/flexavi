<?php

namespace App\Jobs\Employee;

use App\Mail\Employee\EmployeeInvited;
use App\Models\Employee\EmployeeInvitation;
use App\Services\Log\LogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmployeeInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Inviting timeout seconds.
     *
     * @var int
     */
    public int $timeout = 7200;

    /**
     * SendEmployeeInvitation object
     *
     * @var EmployeeInvitation
     */
    public EmployeeInvitation $employeeInvitation;

    /**
     * Create a new job instance.
     *
     * @param EmployeeInvitation $employeeInvitation
     */
    public function __construct(EmployeeInvitation $employeeInvitation)
    {
        $this->employeeInvitation = $employeeInvitation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $employeeInvitation = $this->employeeInvitation;
        $employeeInvitation->refresh();

        if ($employeeInvitation->isCancelled()) {
            LogService::make('employee_invitation.cancel')
                ->on($employeeInvitation)
                ->write();

            return;
        }

        // Execute the sending
        $mail = new EmployeeInvited($employeeInvitation);
        Mail::to($employeeInvitation->invited_email)->send($mail);

        // Mark record as sent
        $employeeInvitation->sent();
    }
}
