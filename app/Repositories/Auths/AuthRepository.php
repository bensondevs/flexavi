<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\Base\BaseRepository;

use App\Mail\Auth\VerifyEmail;
use App\Jobs\{
	SendMail,
	Auth\SendResetPasswordToken
};
use App\Models\{
	User,
	Customer,
	EmailVerification,
	RegisterInvitation
};

class AuthRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new User);
	}

	/**
	 * Register user using array with user data
	 * 
	 * @param array  $registerData
	 * @return \App\Models\User
	 */
	public function register(array $registerData)
	{
		try {
			$user = $this->getModel();
			$user->fill($registerData);
			$user->unhashed_password = $registerData['password'];
			if ($profilePicture = $registerData['profile_picture']) {
				$user->profile_picture = $profilePicture;
			}
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully register as user.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to register as user.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Send user email verification
	 * 
	 * @return bool
	 */
	public function sendEmailVerification()
	{
		try {
			$user = $this->getModel();
			$sendJob = new SendMail(new VerifyEmail($user), $user->email);
			dispatch($sendJob);

			$this->setSuccess('Successfully send email verification.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to send email verification', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Verify email using encrypted code
	 * 
	 * @param string  $encryptedCode
	 * @return \App\Models\EmailVerification
	 */
	public function verifyEmail(string $encryptedCode)
	{
		try {
			$decryptedCode = Crypt::decryptString($encryptedCode);
			$verification = EmailVerification::findByCodeOrFail($decryptedCode);
			$verification->verify();

			$this->setSuccess('Successfully verify email address');	
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to verify email address.', $error);
		}

		return $verification;
	}

	/**
	 * Execute API Login
	 * 
	 * @param array  $credentials
	 * @return \App\Models\User
	 */
	public function login(array $credentials)
	{
		try {
			// Collect credentials
			$email = $credentials['email'];
			$password = $credentials['password'];

			// Is there any user with the inserted email?
			if (! $user = $this->getModel()->findByEmail($email)) {
				$this->setNotFound('User not found!');
				return null;
			}

			// Is the inserted password match with user record?
			if (! $user->isPasswordMatch($password)) {
				$this->setUnprocessedInput('Password mismatch the record!');
				return null;
			}

			// Authenticate the user and set the token
			$user->generateToken();
			Auth::login($user);

			$this->setModel($user);

			$this->setSuccess('Successfully login');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to do login, there is something wrong and we don\' know yet', $error);
		}

		return $this->getModel();
	}

	/**
	 * Execute user logout
	 * 
	 * @return bool
	 */
	public function logout()
	{
		try {
			$user = $this->getModel();
			$user->tokens()->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully logged out');
		} catch (QueryException $qe) {
			$this->setError('Failed to log out', $qe->getMessage());
		}

		return $this->returnResponse();
	}

	/**
	 * Send reset password token to user email
	 * 
	 * @return bool
	 */
	public function sendResetPasswordToken()
	{
		try {
			$user = $this->getModel();
			if ($token = $user->resetPasswordToken) {
				$token->delete();
			}

			$job = new SendResetPasswordToken($user);
			dispatch($job);

			$this->setSuccess('Successfully send reset password token.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to send reset password token.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Change user password using unhashed password
	 * 
	 * @param string  $password
	 * @return \App\Models\User
	 */
	public function changePassword(string $password)
	{
		try {
			$user = $this->getModel();
			$user->unhashed_password = $password;
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully change password.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to change password', $error);
		}

		return $this->getModel();
	}

	/**
	 * Claim reset password token
	 * 
	 * @return \App\Models\User
	 */
	public function claimResetPasswordToken()
	{
		try {
			$user = $this->getModel();
			$user->resetPasswordToken()->delete();

			$this->setModel($user);

			$this->setSuccess('Successfully claim reset password token.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to claim reset password.');
		}

		return $this->getModel();
	}
}
