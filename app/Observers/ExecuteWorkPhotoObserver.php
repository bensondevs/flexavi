<?php

namespace App\Observers;

use App\Models\ExecuteWork\ExecuteWorkPhoto;
use App\Models\StorageFile\StorageFile;

class ExecuteWorkPhotoObserver
{
    /**
     * Handle the ExecuteWorkPhoto "creating" event.
     *
     * @param ExecuteWorkPhoto $executeWorkPhoto
     * @return void
     */
    public function creating(ExecuteWorkPhoto $executeWorkPhoto)
    {
        $executeWorkPhoto->id = generateUuid();
    }

    /**
     * Handle the ExecuteWorkPhoto "created" event.
     *
     * @param ExecuteWorkPhoto $executeWorkPhoto
     * @return void
     */
    public function created(ExecuteWorkPhoto $executeWorkPhoto)
    {
        //
    }

    /**
     * Handle the ExecuteWorkPhoto "updated" event.
     *
     * @param ExecuteWorkPhoto $executeWorkPhoto
     * @return void
     */
    public function updated(ExecuteWorkPhoto $executeWorkPhoto)
    {
        //
    }

    /**
     * Handle the ExecuteWorkPhoto "deleted" event.
     *
     * @param ExecuteWorkPhoto $executeWorkPhoto
     * @return void
     */
    public function deleted(ExecuteWorkPhoto $executeWorkPhoto)
    {
        $photoPath = $executeWorkPhoto->photo_path;
        if ($file = StorageFile::findByPath($photoPath)) {
            $countDownDate = now()->addDays(3);
            $file->setDestroyCountDown($countDownDate);
        }
    }

    /**
     * Handle the ExecuteWorkPhoto "restored" event.
     *
     * @param ExecuteWorkPhoto $executeWorkPhoto
     * @return void
     */
    public function restored(ExecuteWorkPhoto $executeWorkPhoto)
    {
        //
    }

    /**
     * Handle the ExecuteWorkPhoto "force deleted" event.
     *
     * @param ExecuteWorkPhoto $executeWorkPhoto
     * @return void
     */
    public function forceDeleted(ExecuteWorkPhoto $executeWorkPhoto)
    {
        $photoPath = $executeWorkPhoto->photo_path;
        if ($file = StorageFile::findByPath($photoPath)) {
            $file->deleteFile();
        }
    }
}
