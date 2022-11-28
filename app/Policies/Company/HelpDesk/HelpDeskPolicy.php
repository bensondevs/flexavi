<?php

namespace App\Policies\Company\HelpDesk;

use App\Models\HelpDesk\HelpDesk;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HelpDeskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any help desks');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param HelpDesk $helpDesk
     * @return bool
     */
    public function view(User $user, HelpDesk $helpDesk): bool
    {
        return $user->hasCompanyDirectPermission(
            $helpDesk->company_id,
            'view help desks'
        );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('store help desks');
    }

    /**
     * Determine whether the user can update models.
     *
     * @param User $user
     * @param HelpDesk $helpDesk
     * @return bool
     */
    public function update(User $user, HelpDesk $helpDesk): bool
    {
        return $user->hasCompanyDirectPermission($helpDesk->company_id, 'restore owners');
    }

    /**
     * Determine whether the user can delete models.
     *
     * @param User $user
     * @param HelpDesk $helpDesk
     * @return bool
     */
    public function delete(User $user, HelpDesk $helpDesk): bool
    {
        return $user->hasCompanyDirectPermission($helpDesk->company_id, 'restore owners');
    }
}
