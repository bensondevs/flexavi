<?php

namespace App\Broadcasting\Notification;

use App\Models\User\User;

class NotificationChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @param string $userId
     * @return bool
     */
    public function join(User $user, string $userId): bool
    {
        return $user->id === $userId;
    }
}
