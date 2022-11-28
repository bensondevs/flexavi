<?php

namespace App\Observers;

use App\Models\PostIt\PostIt;

class PostItObserver
{
    /**
     * Handle the PostIt "creating" event.
     *
     * @param PostIt $postIt
     * @return void
     */
    public function creating(PostIt $postIt)
    {
        $postIt->id = generateUuid();
    }

    /**
     * Handle the PostIt "created" event.
     *
     * @param PostIt $postIt
     * @return void
     */
    public function created(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "updated" event.
     *
     * @param PostIt $postIt
     * @return void
     */
    public function updated(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "deleted" event.
     *
     * @param PostIt $postIt
     * @return void
     */
    public function deleted(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "restored" event.
     *
     * @param PostIt $postIt
     * @return void
     */
    public function restored(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "force deleted" event.
     *
     * @param PostIt $postIt
     * @return void
     */
    public function forceDeleted(PostIt $postIt)
    {
        //
    }
}
