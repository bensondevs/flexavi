<?php

namespace App\Policies\Company\Notification;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view notifications');
    }

    public function markRead(User $user)
    {
        return $user->hasDirectPermissionTwo('mark read notifications');
    }

    public function markUnread(User $user)
    {
        return $user->hasDirectPermissionTwo('mark unread notifications');
    }

    public function markAllRead(User $user)
    {
        return $user->hasDirectPermissionTwo('mark read all notifications');
    }

    public function markAllUnread(User $user)
    {
        return $user->hasDirectPermissionTwo('mark unread all notifications');
    }
}
