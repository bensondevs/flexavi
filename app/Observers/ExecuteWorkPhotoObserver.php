<?php

namespace App\Observers;

use App\Models\StorageFile;
use App\Models\ExecuteWorkPhoto;

class ExecuteWorkPhotoObserver
{
    /**
     * Handle the ExecuteWorkPhoto "created" event.
     *
     * @param  \App\Models\ExecuteWorkPhoto  $executeWorkPhoto
     * @return void
     */
    public function created(ExecuteWorkPhoto $executeWorkPhoto)
    {
        //
    }

    /**
     * Handle the ExecuteWorkPhoto "updated" event.
     *
     * @param  \App\Models\ExecuteWorkPhoto  $executeWorkPhoto
     * @return void
     */
    public function updated(ExecuteWorkPhoto $executeWorkPhoto)
    {
        //
    }

    /**
     * Handle the ExecuteWorkPhoto "deleted" event.
     *
     * @param  \App\Models\ExecuteWorkPhoto  $executeWorkPhoto
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
     * @param  \App\Models\ExecuteWorkPhoto  $executeWorkPhoto
     * @return void
     */
    public function restored(ExecuteWorkPhoto $executeWorkPhoto)
    {
        //
    }

    /**
     * Handle the ExecuteWorkPhoto "force deleted" event.
     *
     * @param  \App\Models\ExecuteWorkPhoto  $executeWorkPhoto
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
