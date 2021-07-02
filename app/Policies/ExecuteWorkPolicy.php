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

    public function execute(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission();
    }

    public function markFinish(User $user, ExecuteWork $executeWork)
    {
        //
    }

    public function markUnfinished(User $user)
    {
        //
    }

    public function makeContinuation(User $user)
    {
        //
    }
}
