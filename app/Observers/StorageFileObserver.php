<?php

namespace App\Observers;

use App\Models\StorageFile;

class StorageFileObserver
{
    /**
     * Handle the StorageFile "created" event.
     *
     * @param  \App\Models\StorageFile  $storageFile
     * @return void
     */
    public function created(StorageFile $storageFile)
    {
        //
    }

    /**
     * Handle the StorageFile "updated" event.
     *
     * @param  \App\Models\StorageFile  $storageFile
     * @return void
     */
    public function updated(StorageFile $storageFile)
    {
        //
    }

    /**
     * Handle the StorageFile "deleted" event.
     *
     * @param  \App\Models\StorageFile  $storageFile
     * @return void
     */
    public function deleted(StorageFile $storageFile)
    {
        //
    }

    /**
     * Handle the StorageFile "restored" event.
     *
     * @param  \App\Models\StorageFile  $storageFile
     * @return void
     */
    public function restored(StorageFile $storageFile)
    {
        //
    }

    /**
     * Handle the StorageFile "force deleted" event.
     *
     * @param  \App\Models\StorageFile  $storageFile
     * @return void
     */
    public function forceDeleted(StorageFile $storageFile)
    {
        //
    }
}
