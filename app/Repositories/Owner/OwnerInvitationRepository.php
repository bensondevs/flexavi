<?php

namespace App\Repositories\Owner;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Models\Owner\OwnerInvitation;
use App\Models\User\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;

class OwnerInvitationRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new OwnerInvitation());
    }

    /**
     * Invite owner to register to the application
     *
     * @param string $email
     * @param array $ownerData
     * @return OwnerInvitation|null
     */
    public function inviteOwner(
        string $email,
        array  $ownerData
    ): ?OwnerInvitation {
        $invitationData = [
            'invited_email' => $email,
        ];
        $data = array_merge($invitationData, $ownerData);

        return $this->createAndSend($data);
    }

    /**
     * Create and Send invitation to the pre-registered user
     *
     * @param array $invitationData
     * @return ?OwnerInvitation
     */
    public function createAndSend(array $invitationData): ?OwnerInvitation
    {
        try {
            $invitation = $this->getModel();
            $invitation->fill(Arr::only($invitationData, [
                'invited_email',
                'registration_code',
                'company_id',
                'name',
                'phone',
                'expiry_time',
                'status',
                'permissions'
            ]));
            $invitation->save();

            $this->setModel($invitation);
            $this->setSuccess('Your invitation has been sent to employee.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to invite user to register', $error);
        }

        return $this->getModel();
    }

    /**
     * Set used owner invitation
     *
     * @return OwnerInvitation|null
     */
    public function setUsed(): ?OwnerInvitation
    {
        try {
            $invitation = $this->getModel();
            $invitation->status = OwnerInvitationStatus::Used;
            $invitation->save();
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Cancel owner invitation
     *
     * @return bool
     */
    public function cancel(): bool
    {
        try {
            $invitation = $this->getModel();
            $invitation->forceDelete();
            $this->destroyModel();
            $this->setSuccess('Successfully cancel invitation.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->returnResponse();
    }

    /**
    * Accept an invitation
    *
    * @return OwnerInvitation|null
    */
    public function accept(): ?OwnerInvitation
    {
        try {
            $invitation = $this->getModel();
            $permissionNames = $invitation->permissions;

            // invited user instance
            $user = User::query()->firstOrNew([
                'registration_code' => $invitation->registration_code
            ])->fill([
                    'fullname' => $invitation->name ,
                    'email' => $invitation->invited_email ,
                    'phone' => $invitation->phone ,
                ])
                ->assignRole('owner')
                ->syncPermissions($permissionNames);
            $user->save();

            // invited owner instance
            $owner = ($invitation->role_model)->fill([
                'company_id' => $invitation->company_id,
                'user_id' => $user->id,
            ]);
            $owner->save();

            $invitation->status = OwnerInvitationStatus::Used;
            $invitation->save();
            $this->setSuccess('Successfully handle invitation fulfillment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->getModel();
    }
}
