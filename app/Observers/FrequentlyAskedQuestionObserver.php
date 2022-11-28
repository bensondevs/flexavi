<?php

namespace App\Observers;

use App\Models\FAQ\FrequentlyAskedQuestion;

class FrequentlyAskedQuestionObserver
{
    /**
     * Handle the FrequentlyAskedQuestion "creating" event.
     *
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    public function creating(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        $frequentlyAskedQuestion->id = generateUuid();
    }

    /**
     * Handle the FrequentlyAskedQuestion "created" event.
     *
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    public function created(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        //
    }

    /**
     * Handle the FrequentlyAskedQuestion "updated" event.
     *
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    public function updated(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        //
    }

    /**
     * Handle the FrequentlyAskedQuestion "deleted" event.
     *
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    public function deleted(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        //
    }

    /**
     * Handle the FrequentlyAskedQuestion "restored" event.
     *
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    public function restored(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        //
    }

    /**
     * Handle the FrequentlyAskedQuestion "force deleted" event.
     *
     * @param FrequentlyAskedQuestion $frequentlyAskedQuestion
     * @return void
     */
    public function forceDeleted(FrequentlyAskedQuestion $frequentlyAskedQuestion)
    {
        //
    }
}
