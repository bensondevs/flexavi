<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use Socialite;

use App\Mail\Auth\VerifyEmail;

use App\Jobs\SendMail;
use App\Jobs\Auth\SendResetPasswordToken;

use App\Models\User;
use App\Models\Customer;
use App\Models\EmailVerification;
use App\Models\RegisterInvitation;

use App\Repositories\EmployeeRepository;
use App\Repositories\Base\BaseRepository;

class AuthRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new User);
	}

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

		return;
	}

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

	public function login(array $credentials)
	{
		try {
			// Collect credentials
			$email = $credentials['email'];
			$password = $credentials['password'];

			// Find user
			$user = $this->getModel()->where('email', $email)->first();
			if (! $user) {
				$this->setNotFound('User not found!');
				return null;
			}
			$this->setModel($user); // Found!

			// Check if password matched the record
			if (! hashCheck($password, $user->password)) {
				$this->setUnprocessedInput('Password mismatch the record!');
				return null;
			}

			// API Login Token
			$user->token = $user->createToken(time())->plainTextToken;

			$this->setModel($user);

			$this->setSuccess('Successfully login');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to do login, there is something wrong and we don\' know yet', $error);
		}

		return $this->getModel();
	}

	public function customerLogin(array $credentials)
	{
		try {
			if (! $customer = Customer::findUsingCredentials($credentials)) {
				return $this->setNotFound('Customer account not found.');
			}

			if (! $customer = $customer->attemptAutenticate($credentials['unique_key'])) {
				return $this->setUnprocessedInput('Failed to logging in, the unique key does not match out record.');
			}

			$this->setSuccess('Successfully logged in as customer');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to login to customer.', $error);
		}

		return $customer;
	}

	public function socialiteLogin($driver)
	{
		try {
			$socialiteUser = Socialite::driver($driver)
	            ->stateless()
	            ->user();
			$user = $this->getModel()
				->where('email', $socialiteUser->getEmail())
				->first();

			if (! $user) {
				return $this->setNotFound('This user is not yet registered.');
			}

			/*
				Login the found user
			*/
			$user->token = $user->createToken(time())->plainTextToken;

			$this->setModel($user);

			$this->setSuccess('Successfully login.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to execute login from social media', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function socialiteRegister($driver)
	{
		try {
			$socialiteUser = Socialite::driver($driver)
	            ->stateless()
	            ->user();

			$user = new User;
			$user->id = generateUUID();
			$user->fullname = $socialiteUser->getName();
			$user->email = $socialiteUser->getEmail();
			$user->profile_picture_url = $socialiteUser->getAvatar();

			$this->setModel($user);

			$this->setSuccess('Successfully do socialite register');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to register user through social media.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

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

	public function customerLogout(Customer $customer)
	{
		try {
			$customer->tokens()->delete();

			$this->setSuccess('Successfully logged out');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to log out', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}

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
