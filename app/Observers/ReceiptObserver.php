<?php

namespace App\Observers;

use App\Models\Receipt;
use App\Models\StorageFile;

class ReceiptObserver
{
    /**
     * Handle the Receipt "created" event.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return void
     */
    public function created(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the Receipt "updated" event.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return void
     */
    public function updated(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the Receipt "deleted" event.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return void
     */
    public function deleted(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the Receipt "restored" event.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return void
     */
    public function restored(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the Receipt "force deleted" event.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return void
     */
    public function forceDeleted(Receipt $receipt)
    {
        $filePath = $receipt->receipt_path;
        if ($file = StorageFile::findByPath($filePath)) {
            $file->delete();
        }
    }
}
