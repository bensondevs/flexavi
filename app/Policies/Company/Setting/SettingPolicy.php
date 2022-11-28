<?php

namespace App\Policies\Company\Setting;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class SettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any quotations.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any settings');
    }

    /**
     * Determine whether the user can view Setting.
     *
     * @param  \App\Models\User\User  $user
     * @param  Model $Setting
     * @return bool
     */
    public function view(User $user, Model $setting)
    {
        return $user->hasDirectPermissionTwo('view settings');
    }

    /**
     * Determine whether the user can edit/update setting .
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasDirectPermissionTwo('edit settings');
    }
}
