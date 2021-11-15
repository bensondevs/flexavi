<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\{ User, PostIt, PostItAssignedUser };

class PostItPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any post its');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PostIt $postIt)
    {
        return $user->hasCompanyPermission($postIt->company_id, 'view post its');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create post its');
    }

    /**
     * Determine whether the user can assign user to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostIt  $postIt
     * @param  
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assignUser(User $user, PostIt $postIt, User $assignedUser)
    {
        if ($assignedUser->company->id !== $postIt->company->id) {
            return abort(403, 'This user is not from the company');
        }

        return $user->hasCompanyPermission($postIt->company_id, 'assign user post its');
    }

    /**
     * Determine whether the user can unassign user from the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unassignUser(User $user, PostItAssignedUser $pivot)
    {
        $postIt = $pivot->postIt;
        return $user->hasCompanyPermission($postIt->company_id, 'unassign user post its');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PostIt $postIt)
    {
        return $user->hasCompanyPermission($postIt->company_id, 'edit post its');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PostIt $postIt)
    {
        return $user->hasCompanyPermission($postIt->company_id, 'delete post its');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PostIt $postIt)
    {
        return $user->hasCompanyPermission($postIt->company_id, 'restore post its');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostIt  $postIt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PostIt $postIt)
    {
        return $user->hasCompanyPermission($postIt->company_id, 'force delete post its');
    }
}
