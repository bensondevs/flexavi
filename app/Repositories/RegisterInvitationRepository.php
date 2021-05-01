<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\RegisterInvitation;

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

			$this->setModel($invitation);

			$this->setSuccess('Successfully invite user to register');
		} catch (QueryException $qe) {
			$this->setError('Failed to invite user to register');
		}

		return $this->getModel();
	}

	public function createInvitationUrl()
	{
		
	}
}
