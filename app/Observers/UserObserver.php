<?php

namespace App\Observers;

use App\Jobs\SendMail;
use App\Mail\Auth\VerifyEmail;
use App\Models\{StorageFile\StorageFile, User\User};
use App\Services\Log\LogService;

class UserObserver
{

    /**
     * Handle the User "creating" event.
     *
     * @param User $user
     * @return void
     */
    public function creating(User $user): void
    {
        $user->id = generateUuid();
    }

    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user): void
    {
        if (!$user->email_verified_at) {
            $sendJob = new SendMail(new VerifyEmail($user), $user->email);
            dispatch($sendJob);
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('email')) {
            $user->unverifyEmail();
            $user->sendEmailVerification();
            LogService::make("user.updates.email")->by(request()->user())->on($user)->write();
        }

        if ($user->isDirty('fullname'))
            LogService::make("user.updates.fullname")->by(request()->user())->on($user)->write();

        if ($user->isDirty('phone'))
            LogService::make("user.updates.phone")->by(request()->user())->on($user)->write();

        if ($user->isDirty('profile_picture_path')) {
            $originalPath = $user->getOriginal('profile_picture_path');
            $file = StorageFile::findByPath($originalPath);
            if ($file) $file->delete();
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user): void
    {
        if (!is_null($user->profile_picture_path)) {
            $date = now()->addDays(3);
            $file = StorageFile::findByPath($user->profile_picture_path);
            $file->setDestroyCountDown($date);
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user): void
    {
        if (!is_null($user->profile_picture_path)) {
            $file = StorageFile::findByPath($user->profile_picture_path);
            $file->stopDestroyCountDown();
        }
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user): void
    {
        if (!is_null($user->profile_picture_path)) {
            $file = StorageFile::findByPath($user->profile_picture_path);
            $file->delete();
        }
    }
}
