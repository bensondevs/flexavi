<?php

namespace App\Repositories\Invitation;

use App\Enums\RegisterInvitation\RegisterInvitationStatus as Status;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Invitation\RegisterInvitation;
use App\Models\Owner\OwnerInvitation;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Employee\EmployeeInvitationRepository;
use App\Repositories\Owner\OwnerInvitationRepository;
use DateTime;
use Illuminate\Database\QueryException;

class RegisterInvitationRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new RegisterInvitation());
    }

    /**
     * Invite employee to register to the application
     *
     * @param string $email
     * @param array $employeeData
     * @param DateTime $expiryTime
     * @return RegisterInvitation|null
     */
    public function inviteEmployee(
        string $email,
        array  $employeeData,
               $expiryTime = null
    ): ?RegisterInvitation
    {
        $invitationData = [
            'invited_email' => $email,
            'expiry_time' => $expiryTime ?: now()->addDays(3),
            'attachments' => $employeeData,
            'role' => 'employee',
        ];

        return $this->sendInvitation($invitationData);
    }

    /**
     * Send invitation to the pre-registered user
     *
     * @param array $invitationData
     * @return RegisterInvitation|null
     */
    public function sendInvitation(array $invitationData): ?RegisterInvitation
    {
        try {
            $invitation = $this->getModel();
            $invitation->fill($invitationData);
            $invitation->attachments = $invitationData['attachments'];
            $invitation->save();
            $this->setModel($invitation);
            $this->setSuccess('Successfully invite user to register');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to invite user to register', $error);
        }

        return $this->getModel();
    }

    /**
     * Invite owner to register to the application
     *
     * @param string $email
     * @param array $ownerData
     * @param DateTime $expiryTime
     * @return RegisterInvitation|null
     */
    public function inviteOwner(
        string $email,
        array  $ownerData,
               $expiryTime = null
    ): ?RegisterInvitation
    {
        $invitationData = [
            'invited_email' => $email,
            'expiry_time' => $expiryTime,
            'attachments' => $ownerData,
            'role' => 'owner',
        ];

        return $this->sendInvitation($invitationData);
    }

    /**
     * Handle invitation using process
     *
     * @return RegisterInvitation|null
     */
    public function handleInvitationFulfilled(): ?RegisterInvitation
    {
        try {
            $invitation = $this->getModel();
            $invitationable = $invitation->invitationable;

            $repository = match (true) {
                $invitationable instanceof OwnerInvitation =>
                    app(OwnerInvitationRepository::class),

                $invitationable instanceof  EmployeeInvitation =>
                    app(EmployeeInvitationRepository::class),

                default => abort(500, 'Unknown type of invitationable.'),
            };
            $repository->setModel($invitationable);
            $repository->handleInvitationFulfilled();

            $this->markAsUsed();
            $this->setSuccess($repository->message);
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Mark register invitation as used
     *
     * @return RegisterInvitation|null
     */
    public function markAsUsed(): ?RegisterInvitation
    {
        try {
            $invitation = $this->getModel();
            $invitation->status = Status::Used;
            $invitation->save();
            $this->setModel($invitation);
            $this->setSuccess('Successfully mark registration invitation as used');
        } catch (QueryException $qe) {
            $this->setError('Failed to mark registration invitation as used');
        }

        return $this->getModel();
    }
}
