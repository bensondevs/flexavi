<?php

namespace App\Repositories\User;

use App\Models\User\EmailVerification;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class EmailVerificationRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new EmailVerification());
    }

    /**
     * Send verification
     *
     * @param array $verificationData
     * @return EmailVerification|null
     */
    public function sendVerification(array $verificationData)
    {
        try {
            $verification = $this->getModel();
            $verification->fill($verificationData);
            $verification->verification_status = 'waiting';
            $verification->save();
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

    /**
     * Verify email verification
     *
     * @return EmailVerification|null
     */
    public function verify()
    {
        try {
            $verification = $this->getModel();
            $model = $verification->model;
            $model = $model::findOrFail($verification->model_id);
            $model->{$verification->model_verification_column} = carbon()->now();
            $model->save();
            $verification->verification_status = 'verified';
            $verification->save();
            $this->setModel($verification);
            $this->setSuccess('Successfully verify email.');
        } catch (QueryException $qe) {
            $this->setError('Failed to verify email', $qe->getMessage());
        }

        return $this->getModel();
    }
}
