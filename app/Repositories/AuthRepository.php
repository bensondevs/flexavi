<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\User;

use App\Repositories\Base\BaseRepository;

class AuthRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new User);
	}

	public function register($registerData)
	{
		try {
			$user = $this->getModel();
			$user->fill($registerData);
			$user->unhashed_password = $registerData['password'];
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully register as user.');
		} catch (QueryException $qe) {
			$this->setError('Failed to register as user.', $qe->getMessage());
		}

		return $this->getModel();
	}

	public function login($credentials)
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
			$this->setError(
				'Failed to do login, there is something wrong and we don\' know yet', 
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
}
