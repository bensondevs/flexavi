<?php

namespace App\Observers;

use App\Models\PostIt\PostItAssignedUser;

class PostItAssignedUserObserver
{
    /**
     * Handle the PostItAssignedUser "creating" event.
     *
     * @param PostItAssignedUser $postItAssignedUser
     * @return void
     */
    public function creating(PostItAssignedUser $postItAssignedUser)
    {
        $postItAssignedUser->id = generateUuid();
    }

    /**
     * Handle the PostItAssignedUser "created" event.
     *
     * @param PostItAssignedUser $postItAssignedUser
     * @return void
     */
    public function created(PostItAssignedUser $postItAssignedUser)
    {
        //
    }

    /**
     * Handle the PostItAssignedUser "updated" event.
     *
     * @param PostItAssignedUser $postItAssignedUser
     * @return void
     */
    public function updated(PostItAssignedUser $postItAssignedUser)
    {
        //
    }

    /**
     * Handle the PostItAssignedUser "deleted" event.
     *
     * @param PostItAssignedUser $postItAssignedUser
     * @return void
     */
    public function deleted(PostItAssignedUser $postItAssignedUser)
    {
        //
    }

    /**
     * Handle the PostItAssignedUser "restored" event.
     *
     * @param PostItAssignedUser $postItAssignedUser
     * @return void
     */
    public function restored(PostItAssignedUser $postItAssignedUser)
    {
        //
    }

    /**
     * Handle the PostItAssignedUser "force deleted" event.
     *
     * @param PostItAssignedUser $postItAssignedUser
     * @return void
     */
    public function forceDeleted(PostItAssignedUser $postItAssignedUser)
    {
        //
    }
}
