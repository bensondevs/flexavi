<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\User;

class UserRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new User);
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
			$this->setError('Failed to save user data', $qe->getMessage);
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
			$this->setError('Failed to delete user data.', $qe->getMessage());
		}

		return $delete;
	}
}