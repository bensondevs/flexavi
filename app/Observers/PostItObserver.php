<?php

namespace App\Observers;

use App\Models\PostIt;

class PostItObserver
{
    /**
     * Handle the PostIt "creating" event.
     *
     * @param  \App\Models\PostIt  $postIt
     * @return void
     */
    public function creating(PostIt $postIt)
    {
        $postIt->id = generateUuid();
    }

    /**
     * Handle the PostIt "created" event.
     *
     * @param  \App\Models\PostIt  $postIt
     * @return void
     */
    public function created(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "updated" event.
     *
     * @param  \App\Models\PostIt  $postIt
     * @return void
     */
    public function updated(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "deleted" event.
     *
     * @param  \App\Models\PostIt  $postIt
     * @return void
     */
    public function deleted(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "restored" event.
     *
     * @param  \App\Models\PostIt  $postIt
     * @return void
     */
    public function restored(PostIt $postIt)
    {
        //
    }

    /**
     * Handle the PostIt "force deleted" event.
     *
     * @param  \App\Models\PostIt  $postIt
     * @return void
     */
    public function forceDeleted(PostIt $postIt)
    {
        //
    }
}
