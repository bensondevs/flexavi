<?php

namespace App\Console\Commands\Employee;

use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Employee\EmployeeInvitation;
use Illuminate\Console\Command;

class UpdateRegisterInvitationsStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:update-invitation-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update employee invitations statuses.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        EmployeeInvitation::whereStatus(EmployeeInvitationStatus::Active)
            ->where('expiry_time', '>=', now()->startOfDay()->toDateTimeString())
            ->update([
                'status' => EmployeeInvitationStatus::Expired,
                'marked_expired_at' => now()->toDateTimeString(),
            ]);

        return 0;
    }
}
