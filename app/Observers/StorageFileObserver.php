<?php

namespace App\Observers;

use App\Models\StorageFile\StorageFile;

class StorageFileObserver
{
    /**
     * Handle the StorageFile "created" event.
     *
     * @param StorageFile $storageFile
     * @return void
     */
    public function created(StorageFile $storageFile)
    {
        //
    }

    /**
     * Handle the StorageFile "updated" event.
     *
     * @param StorageFile $storageFile
     * @return void
     */
    public function updated(StorageFile $storageFile)
    {
        if ($storageFile->isDirty('path') || $storageFile->isDirty('disk')) {
            $path = $storageFile->getOriginal('path');
            $disk = $storageFile->getOriginal('disk');

            StorageFile::destroyFile($path, $disk);
        }
    }

    /**
     * Handle the StorageFile "deleted" event.
     *
     * @param StorageFile $storageFile
     * @return void
     */
    public function deleted(StorageFile $storageFile)
    {
        $storageFile->detonateFile();
    }

    /**
     * Handle the StorageFile "restored" event.
     *
     * @param StorageFile $storageFile
     * @return void
     */
    public function restored(StorageFile $storageFile)
    {
        //
    }

    /**
     * Handle the StorageFile "force deleted" event.
     *
     * @param StorageFile $storageFile
     * @return void
     */
    public function forceDeleted(StorageFile $storageFile)
    {
        $storageFile->detonateFile();
    }
}
