<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\User;
use App\Models\Role;

class UserRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new User);
	}

	public function hasRole($role)
	{
		$role = ($role instanceof Role) ?: 
			Role::findByName($role);
		$users = $role->users()->get();

		return $this->setCollection($users);
	}

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
			$this->setError('Failed to save user data', $qe->getMessage());
		}

		return $this->getModel();
	}

	public function setProfilePicture($pictureFile)
	{
		try {
			$user = $this->getModel();
			$user->profile_picture = $pictureFile;
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully set user profile picture.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to set user profile picture.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
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
			$this->setError('Failed to change user password.', $qe->getMessage());
		}

		return $this->getModel();
	}

	public function delete($forceDelete = false)
	{
		try {
			$user = $this->getModel();
			$delete = $forceDelete ?
				$user->forceDelete() : 
				$user->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete user data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete user data.', $error);
		}

		return $delete;
	}
}