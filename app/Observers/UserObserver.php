<?php

namespace App\Observers;

use App\Models\User;
use App\Models\StorageFile;

use App\Repositories\AuthRepository;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if (! $user->email_verified_at) {
            $user->sendEmailVerification();
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->isDirty('email')) {
            $user->unverifyEmail();
            $user->sendEmailVerification();
        }

        if ($user->isDirty('profile_picture_path')) {
            $originalPath = $user->getOriginal('profile_picture_path');
            $file = StorageFile::findByPath($originalPath);
            $file->delete();
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        if ($file = StorageFile::findByPath($user->profile_picture_path)) {
            $date = now()->addDays(3);
            $file->setDestroyCountDown($date);
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        if ($file = StorageFile::findByPath($user->profile_picture_path)) {
            $file->stopDestroyCountDown();
        }
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        if ($file = StorageFile::findByPath($user->profile_picture_path)) {
            $file->delete();
        }
    }
}
