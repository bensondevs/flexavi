<?php

namespace App\Policies\Company\Employee;

use App\Models\{Employee\EmployeeInvitation, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeInvitationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view pending invitation employees');
    }

    public function view(User $user, EmployeeInvitation $employeeInvitation)
    {
        return $user->hasCompanyDirectPermission($employeeInvitation->company, 'view pending invitation employee');
    }

    public function cancel(User $user, EmployeeInvitation $employeeInvitation)
    {
        return $user->hasCompanyDirectPermission($employeeInvitation->company, 'cancel employee invitations');
    }

    public function store(User $user)
    {
        return $user->hasDirectPermissionTwo('send employee register invitation');
    }
}
