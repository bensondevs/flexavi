<?php

namespace App\Observers;

use App\Models\Employee\EmployeeInvitation;

class EmployeeInvitationObserver
{
    /**
     * Handle the SendEmployeeInvitation "creating" event.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function creating(EmployeeInvitation $employeeInvitation): void
    {
        $employeeInvitation->id = generateUuid();
        $employeeInvitation->registration_code = $employeeInvitation->registration_code ?:
            randomString(6);
        $employeeInvitation->expiry_time = $employeeInvitation->expiry_time ?:
            carbon()->now()->addDays(3);
    }

    /**
     * Handle the SendEmployeeInvitation "created" event.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function created(EmployeeInvitation $employeeInvitation): void
    {
        //
    }

    /**
     * Handle the SendEmployeeInvitation "updated" event.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function updated(EmployeeInvitation $employeeInvitation): void
    {
        //
    }

    /**
     * Handle the SendEmployeeInvitation "deleted" event.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function deleted(EmployeeInvitation $employeeInvitation): void
    {
        //
    }

    /**
     * Handle the SendEmployeeInvitation "restored" event.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function restored(EmployeeInvitation $employeeInvitation): void
    {
        //
    }

    /**
     * Handle the SendEmployeeInvitation "force deleted" event.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function forceDeleted(EmployeeInvitation $employeeInvitation): void
    {
        //
    }
}
