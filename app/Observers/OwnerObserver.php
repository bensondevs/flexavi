<?php

namespace App\Observers;

use App\Models\Owner\Owner;
use App\Services\Log\LogService;
use App\Services\Notification\NotificationService;

class OwnerObserver
{
    /**
     * Handle the Owner "creating" event.
     *
     * @param Owner $owner
     * @return void
     */
    public function creating(Owner $owner): void
    {
        $owner->id = generateUuid();
        if (!$owner->company_id) $owner->is_prime_owner = true;
    }

    /**
     * Handle the Owner "created" event.
     *
     * @param Owner $owner
     * @return void
     */
    public function created(Owner $owner): void
    {
        if ($user = auth()->user()) {
            LogService::make("owner.store")->by($user)->on($owner)->write();

            NotificationService::make("owner.created")
                ->by($user)
                ->on($owner)
                ->extras($owner->load('user')->toArray())
                ->write();
        }
    }

    /**
     * Handle the Owner "updated" event.
     *
     * @param Owner $owner
     * @return void
     */
    public function updated(Owner $owner): void
    {
        if ($user = auth()->user()) {
            NotificationService::make("owner.updated")
                ->by($user)
                ->on($owner)
                ->extras($owner->load('user')->toArray())
                ->write();
            if ($owner->isDirty("is_prime_owner")) {
                LogService::make("owner.updates.is_prime_owner")->by($user)->on($owner)->write();
            }
        }
    }

    /**
     * Handle the Owner "deleted" event.
     *
     * @param Owner $owner
     * @return void
     */
    public function deleted(Owner $owner): void
    {
        if ($user = auth()->user()) {
            NotificationService::make("owner.deleted")
                ->by($user)
                ->on($owner)
                ->extras($owner->load('user')->toArray())
                ->write();

            LogService::make("owner.delete")->by($user)->on($owner)->write();
        }
    }

    /**
     * Handle the Owner "restored" event.
     *
     * @param Owner $owner
     * @return void
     */
    public function restored(Owner $owner): void
    {
        if ($user = auth()->user()) {
            NotificationService::make("owner.restored")
                ->by($user)
                ->on($owner)
                ->extras($owner->load('user')->toArray())
                ->write();

            LogService::make("owner.restore")->by($user)->on($owner)->write();
        }
    }

    /**
     * Handle the Owner "force deleted" event.
     *
     * @param Owner $owner
     * @return void
     */
    public function forceDeleted(Owner $owner): void
    {
        if ($user = auth()->user()) {
            NotificationService::make("owner.permanently_deleted")
                ->by($user)
                ->on($owner)
                ->extras($owner->load('user')->toArray())
                ->write();

            LogService::make("owner.force_delete")->by($user)->on($owner)->write();
        }
    }
}
