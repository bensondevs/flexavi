<?php

namespace App\Observers;

use App\Models\Employee\Employee;
use App\Services\Log\LogService;

class EmployeeObserver
{
    /**
     * Handle the employee "creating" event.
     *
     * @param Employee $employee
     * @return void
     */
    public function creating(Employee $employee): void
    {
        $employee->id = generateUuid();
    }

    /**
     * Handle the employee "created" event.
     *
     * @param Employee $employee
     * @return void
     */
    public function created(Employee $employee): void
    {
        if ($user = auth()->user()) {
            LogService::make("employee.store")
                ->by($user)
                ->on($employee)
                ->write();
        }

    }

    /**
     * Handle the employee "updated" event.
     *
     * @param Employee $employee
     * @return void
     */
    public function updated(Employee $employee): void
    {
        if ($user = auth()->user()) {
            if ($employee->isDirty('employee_type')) {
                LogService::make("employee.updates.type")->by($user)->on($employee)->write();
            }

            if ($employee->isDirty('employment_status')) {
                LogService::make("employee.updates.status")->by($user)->on($employee)->write();
            }
        }
    }

    /**
     * Handle the employee "deleted" event.
     *
     * @param Employee $employee
     * @return void
     */
    public function deleted(Employee $employee): void
    {
        if ($user = auth()->user()) {
            LogService::make("employee.delete")
                ->by($user)->on($employee)->write();
        }
    }

    /**
     * Handle the employee "restored" event.
     *
     * @param Employee $employee
     * @return void
     */
    public function restored(Employee $employee): void
    {
        if ($user = auth()->user()) {
            LogService::make("employee.restore")
                ->by($user)
                ->on($employee)
                ->write();
        }

    }

    /**
     * Handle the employee "force deleted" event.
     *
     * @param Employee $employee
     * @return void
     */
    public function forceDeleted(Employee $employee): void
    {
        //
    }
}
