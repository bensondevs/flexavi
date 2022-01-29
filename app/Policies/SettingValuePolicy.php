<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\{ User, SettingValue };

class SettingValuePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can show company values of a certain setting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SettingValue  $value
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function showCompanyValue(User $user, SettingValue $value)
    {
        return $user->hasCompanyPermission($value->company_id, 'view setting values');
    }

    /**
     * Determine whether the user can set value for company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setForCompany(User $user, Company $company)
    {
        return $user->hasCompanyPermission($company, 'set setting values');
    }

    /**
     * Determine whether the user can reset the company settings to default.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function resetDefaultForCompany(User $user, Company $company)
    {
        return $user->hasCompanyPermission($company, 'reset default setting values');
    }
}
