<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\EmailVerification;

class EmailVerificationRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new EmailVerification);
	}

	public function sendVerification(array $verificationData)
	{
		try {
			$verification = $this->getModel();
			$verification->fill($verificationData);
			$verification->verification_status = 'waiting';
			$verification->save();

			// Send Verification Email

			$this->setModel($verification);

			$this->setSuccess('Successfully send email verification');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to send email verification.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function verify()
	{
		try {
			$verification = $this->getModel();

			// Verification Process
			$model = $verification->model;
			$model = $model::findOrFail($verification->model_id);
			$model->{$verification->model_verification_column} = carbon()->now();
			$model->save();

			$verification->verification_status = 'verified';
			$verification->save();

			$this->setModel($verification);

			$this->setSuccess('Successfully verify email.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to verify email', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}
}
