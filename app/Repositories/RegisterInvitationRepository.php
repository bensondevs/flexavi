<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\RegisterInvitation;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;

use App\Repositories\Base\BaseRepository;

class RegisterInvitationRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new RegisterInvitation);
	}

	public function sendInvitation(array $invitationData)
	{
		try {
			$invitation = $this->getModel();
			$invitation->fill($invitationData);
			$invitation->attachments = $invitationData['attachments'];
			$invitation->save();

			// Send email
			//

			$this->setModel($invitation);

			$this->setSuccess('Successfully invite user to register');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to invite user to register', $error);
		}

		return $this->getModel();
	}

	public function inviteEmployee(string $email, array $employeeData, $expiryTime = null)
	{
		$invitationData = [
			'invited_email' => $email,
			'expiry_time' => $expiryTime ?: now()->addDays(3),
			'attachments' => $employeeData,
			'role' => 'employee',
		];

		return $this->sendInvitation($invitationData);
	}

	public function inviteOwner(string $email, array $ownerData, $expiryTime = null)
	{
		$invitationData = [
			'invited_email' => $email,
			'expiry_time' => $expiryTime,
			'attachments' => $ownerData,
			'role' => 'owner',
		];

		return $this->sendInvitation($invitationData);
	}

	public function handleInvitationFulfilled()
	{
		try {
			$invitation = $this->getModel();
			$user = $invitation->invitedUser;

			// Assign user with role
			$user->assignRole($invitation->role);

			// Create role title data
			$role = $invitation->role_model;
			if ($attachments = $invitation->attachments) {
				$role->fill($attachments);
			}
			$role['user_id'] = $user->id;
			$role->save();

			$invitation->status = 

			$this->setSuccess('Successfully handle invitation fulfillment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to handle invitation fulfillment.', $error);
		}

		return $this->getModel();
	}

	public function findByCode($code)
	{
		$invitation = $this->getModel();
		$invitation = $invitation->findByCode($code);
		$this->setModel($invitation);
	}

	public function markAsUsed()
	{
		try {
			$invitation = $this->getModel();
			$invitation->status = RegisterInvitationStatus::Used;
			$invitation->save();

			$this->setModel($invitation);

			$this->setSuccess('Successfully mark registration invitation as used');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark registration invitation as used');
		}

		return $this->getModel();
	}
}
