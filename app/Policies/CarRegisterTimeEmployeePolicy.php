<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\{
    User,
    Employee,
    CarRegisterTime as Time,
    CarRegisterTimeEmployee as AssignedEmployee
};

use App\Enums\CarRegisterTimeEmployee\PassangerType;

class CarRegisterTimeEmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  Time  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Time $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'view any car register time employees');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  AssignedEmployee  $assignedEmployee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AssignedEmployee $assignedEmployee)
    {
        return $user->hasCompanyPermission($assignedEmployee->company_id, 'view car register time employees');
    }

    /**
     * Determine whether the user can assign models to car register time.
     *
     * @param  \App\Models\User  $user
     * @param  Time  $time
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assign(User $user, Time $time, Employee $employee)
    {
        if ($time->company_id !== $employee->company_id) {
            return abort(403, 'Cannot use data from other company');
        }

        return $user->hasCompanyPermission($time->company_id, 'assign car register time employees');
    }

    /**
     * Determine whether the user can set models passanger type as driver.
     *
     * @param  \App\Models\User  $user
     * @param  AssignedEmployee $assignedEmployee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setAsDriver(User $user, AssignedEmployee $assignedEmployee)
    {
        return $user->hasCompanyPermission($assignedEmployee->company_id, 'set as driver car register time employees');
    }

    /**
     * Determine whether the user can set models out as driver.
     *
     * @param  \App\Models\User  $user
     * @param  AssignedEmployee $assignedEmployee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setOut(User $user, AssignedEmployee $assignedEmployee)
    {
        if ($assignedEmployee->passanger_type == PassangerType::Driver) {
            return abort(403, 'Set out driver is prohibited, please choose another driver before setting this passanger out');
        }

        if ($assignedEmployee->set_out) {
            return abort(422, 'This passanger is already out from the car.');
        }

        return $user->hasCompanyPermission($assignedEmployee->company_id, 'set out car register time employees');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRegisterTimeEmployee  $carRegisterTimeEmployee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unassign(User $user, AssignedEmployee $assignedEmployee)
    {
        return $user->hasCompanyPermission($assignedEmployee->company_id, 'unassign car register time employees');
    }
}
