<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Company;
use App\Models\Work;
use App\Models\ExecuteWork;

class ExecuteWorkPolicy
{
    use HandlesAuthorization;

    public function execute(User $user, Work $Work, Appointment $appointment)
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
        return $user->hasCompanyPermission($executeWork->company_id, 'mark unfinished execute works')
    }

    public function makeContinuation(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'make continuation execute works');
    }
}
