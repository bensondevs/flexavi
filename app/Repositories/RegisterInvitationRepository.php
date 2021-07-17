<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\RegisterInvitation;

use App\Enums\RegisterInvitation\RegiterInvitationStatus;

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
			$this->setError('Failed to invite user to register');
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
