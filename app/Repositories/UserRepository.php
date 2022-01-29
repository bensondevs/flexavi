<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Role };

class UserRepository extends BaseRepository
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
	 * Save user by supplied array input
	 * 
	 * @return \App\Models\User
	 */
	public function save(array $userData)
	{
		try {
			$user = $this->getModel();
			$user->fill($userData);
			$user->unhashed_password = $userData['password'];
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully save user data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save user data', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set profile picture of the user
	 * 
	 * @param  mixed  $pictureFile
	 * @return \App\Models\User
	 */
	public function setProfilePicture($pictureFile)
	{
		try {
			$user = $this->getModel();
			$user->profile_picture = $pictureFile;
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully set user profile picture.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set user profile picture.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Change user password
	 * 
	 * @param  string  $password
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
			$this->setError('Failed to change user password.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete user
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$user = $this->getModel();
			$delete = $force ?
				$user->forceDelete() : 
				$user->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete user data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete user data.', $error);
		}

		return $this->returnResponse();
	}
}