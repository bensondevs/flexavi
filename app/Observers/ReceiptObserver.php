<?php

namespace App\Observers;

use App\Models\{Receipt\Receipt, StorageFile\StorageFile};

class ReceiptObserver
{
    /**
     * Handle the Receipt "creating" event.
     *
     * @param Receipt $receipt
     * @return void
     */
    public function creating(Receipt $receipt)
    {
        $receipt->id = generateUuid();
    }

    /**
     * Handle the Receipt "created" event.
     *
     * @param Receipt $receipt
     * @return void
     */
    public function created(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the Receipt "updated" event.
     *
     * @param Receipt $receipt
     * @return void
     */
    public function updated(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the Receipt "deleted" event.
     *
     * @param Receipt $receipt
     * @return void
     */
    public function deleted(Receipt $receipt)
    {
        $filePath = $receipt->receipt_path;
        if ($file = StorageFile::findByPath($filePath)) {
            $date = now()->addDays(3);
            $file->setDestroyCountDown($date);
        }
    }

    /**
     * Handle the Receipt "restored" event.
     *
     * @param Receipt $receipt
     * @return void
     */
    public function restored(Receipt $receipt)
    {
        $filePath = $receipt->receipt_path;
        if ($file = StorageFile::findByPath($filePath)) {
            $file->stopDestroyCountDown();
        }
    }

    /**
     * Handle the Receipt "force deleted" event.
     *
     * @param Receipt $receipt
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
