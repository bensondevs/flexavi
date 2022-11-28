<?php

namespace App\Console\Commands;

use App\Models\Company\Company;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Invitation\RegisterInvitation;
use App\Models\Owner\OwnerInvitation;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MakeFakeRegistrationInvitation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:fake-registration-invitation {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a fake registration invitation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (config('app.env') !== 'local') {
            $this->error('This command is only available in local environment');
            return CommandAlias::FAILURE;
        }

        if (!in_array($this->argument('type'), ['owner', 'employee'])) {
            $this->error('Invalid type');
            return CommandAlias::FAILURE;
        }

        $type = $this->argument('type');
        $company = Company::factory()->create();
        if ($type === 'owner') {
            $invitationable = OwnerInvitation::factory()->for($company)->create();
            $invitation = RegisterInvitation::factory()->owner()->create([
                'invitationable_type' => get_class($invitationable),
                'invitationable_id' => $invitationable->id,
                'registration_code' => $invitationable->registration_code,
            ]);
        } else {
            $invitationable = EmployeeInvitation::factory()->for($company)->create();
            $invitation = RegisterInvitation::factory()->employee()->create([
                'invitationable_type' => get_class($invitationable),
                'invitationable_id' => $invitationable->id,
                'registration_code' => $invitationable->registration_code,
            ]);
        }
        $invitation = RegisterInvitation::factory()->{$type}()->create();

        $this->info("Fake registration invitation for {$type} has been created successfully! " . "Code : " . $invitation->registration_code);
        return CommandAlias::SUCCESS;
    }
}
