<?php

namespace App\Policies\Company\Car;

use App\Enums\CarRegisterTimeEmployee\PassangerType;
use App\Models\{Car\CarRegisterTime as Time,
    Car\CarRegisterTimeEmployee as AssignedEmployee,
    Employee\Employee,
    User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class CarRegisterTimeEmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @param  Time  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Time $time)
    {
        return $user->hasCompanyDirectPermission($time->company_id, 'view any car register time employees');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  AssignedEmployee  $assignedEmployee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AssignedEmployee $assignedEmployee)
    {
        return $user->hasCompanyDirectPermission($assignedEmployee->company_id, 'view car register time employees');
    }

    /**
     * Determine whether the user can assign models to car register time.
     *
     * @param  \App\Models\User\User  $user
     * @param  Time  $time
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assign(User $user, Time $time, Employee $employee)
    {
        if ($time->company_id !== $employee->company_id) {
            return abort(403, 'Cannot use data from other company');
        }

        return $user->hasCompanyDirectPermission($time->company_id, 'assign car register time employees');
    }

    /**
     * Determine whether the user can set models passanger type as driver.
     *
     * @param  \App\Models\User\User  $user
     * @param  AssignedEmployee $assignedEmployee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setAsDriver(User $user, AssignedEmployee $assignedEmployee)
    {
        return $user->hasCompanyDirectPermission($assignedEmployee->company_id, 'set as driver car register time employees');
    }

    /**
     * Determine whether the user can set models out as driver.
     *
     * @param  \App\Models\User\User  $user
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

        return $user->hasCompanyDirectPermission($assignedEmployee->company_id, 'set out car register time employees');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Car\CarRegisterTimeEmployee  $carRegisterTimeEmployee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unassign(User $user, AssignedEmployee $assignedEmployee)
    {
        return $user->hasCompanyDirectPermission($assignedEmployee->company_id, 'unassign car register time employees');
    }
}
