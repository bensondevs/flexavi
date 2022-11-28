<?php

namespace App\Policies\Company\PostIt;

use App\Models\{PostIt\PostIt, PostIt\PostItAssignedUser, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class PostItPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any post its');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PostIt\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PostIt $postIt)
    {
        return $user->hasCompanyDirectPermission($postIt->company_id, 'view post its');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasDirectPermissionTwo('create post its');
    }

    /**
     * Determine whether the user can assign user to the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PostIt\PostIt  $postIt
     * @param
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assignUser(User $user, PostIt $postIt, User $assignedUser)
    {
        return $user->hasCompanyDirectPermission($postIt->company_id, 'assign user post its');
    }

    /**
     * Determine whether the user can unassign user from the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PostIt\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unassignUser(User $user, PostItAssignedUser $pivot)
    {
        $postIt = $pivot->postIt;
        return $user->hasCompanyDirectPermission($postIt->company_id, 'unassign user post its');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PostIt\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PostIt $postIt)
    {
        return $user->hasCompanyDirectPermission($postIt->company_id, 'edit post its');
    }



    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PostIt\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PostIt $postIt)
    {
        return $user->hasCompanyDirectPermission($postIt->company_id, 'delete post its');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PostIt\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PostIt $postIt)
    {
        return $user->hasCompanyDirectPermission($postIt->company_id, 'restore post its');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PostIt\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PostIt $postIt)
    {
        return $user->hasCompanyDirectPermission($postIt->company_id, 'force delete post its');
    }
}
