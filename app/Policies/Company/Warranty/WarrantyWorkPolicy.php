<?php

namespace App\Policies\Company\Warranty;

use App\Models\{User\User, Warranty\WarrantyWork};
use App\Policies\Warranty;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarrantyWorkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can attach warranty works.
     *
     * @param  \App\Models\User\User      $user
     * @param  \App\Models\Warranty\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function attach(User $user, Warranty $warranty)
    {
        return $user->hasCompanyDirectPermission($warranty->company_id, 'attach warranty works');
    }

    /**
     * Determine whether the user can detach warranty works.
     *
     * @param  \App\Models\User\User      $user
     * @param  \App\Models\Warranty\Warranty  $warrantyWork
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function detach(User $user, WarrantyWork $warrantyWork)
    {
        $warranty = $warrantyWork->warranty;

        return $user->hasCompanyDirectPermission($warranty->company_id, 'detach warranty works');
    }
}
