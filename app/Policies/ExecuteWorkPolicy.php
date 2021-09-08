<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Work;
use App\Models\Company;
use App\Models\Appointment;
use App\Models\ExecuteWork;

class ExecuteWorkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any execute works');
    }

    public function execute(User $user, Work $work, Appointment $appointment)
    {
        if ($work->company_id != $appointment->company_id) {
            return false;
        }

        return $user->hasCompanyPermission($work->company_id, 'execute works');
    }

    public function markFinish(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'mark finish execute works');
    }

    public function markUnfinished(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'mark unfinished execute works');
    }

    public function makeContinuation(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'make continuation execute works');
    }

    public function delete(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'delete execute works');
    }

    public function restore(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'restire execute works');
    }
}
