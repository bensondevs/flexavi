<?php

namespace App\Observers;

use App\Models\{Invitation\RegisterInvitation, User\User};
use App\Models\Owner\OwnerInvitation;
use App\Services\Permission\PermissionService;

class OwnerInvitationObserver
{
    /**
     * Handle the OwnerInvitation "creating" event.
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function creating(OwnerInvitation $ownerInvitation): void
    {
        $ownerInvitation->id = generateUuid();
        $ownerInvitation->registration_code = $ownerInvitation->registration_code ?: randomString(6);
        $ownerInvitation->expiry_time = $ownerInvitation->expiry_time ?: carbon()->now()->addDays(3);
    }

    /**
     * Handle the OwnerInvitation "created" event.
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function created(OwnerInvitation $ownerInvitation): void
    {
        RegisterInvitation::create([
            'invitationable_type' => get_class($ownerInvitation),
            'invitationable_id' => $ownerInvitation->id,
            'registration_code' => $ownerInvitation->registration_code,
            'expiry_time' => $ownerInvitation->expiry_time
        ]);

        if (User::where('email', $ownerInvitation->invited_email)->first()) {
            abort(422, 'Email has been used.');
        }

        dispatch(new \App\Jobs\Owner\OwnerInvitation(
            $ownerInvitation
        ));
    }

    /**
     * Handle the OwnerInvitation "updated" event.
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function updated(OwnerInvitation $ownerInvitation): void
    {
        if ($ownerInvitation->isUsed()) {
            (new PermissionService())->setPermissionFromOwnerInvitation($ownerInvitation);
        }
    }

    /**
     * Handle the OwnerInvitation "deleted" event.
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function deleted(OwnerInvitation $ownerInvitation): void
    {
        //
    }

    /**
     * Handle the OwnerInvitation "restored" event.
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function restored(OwnerInvitation $ownerInvitation): void
    {
        //
    }

    /**
     * Handle the OwnerInvitation "force deleted" event.
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function forceDeleted(OwnerInvitation $ownerInvitation): void
    {
        //
    }
}
