<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\Owner;
use App\Models\Company;

use App\Repositories\Base\BaseRepository;

class CompanyOwnerRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Owner);
	}

	public function inviteables(array $options = [])
	{
		array_push($options['wheres'], [
			'column' => 'user_id',
			'value' => null,
		]);

		return $this->all($options);
	}

	public function save(array $ownerData)
	{
		try {
			$owner = $this->getModel();
			$owner->fill($ownerData);
			$owner->save();

			$this->setModel($owner);

			$this->setSuccess('Successfully save owner.');
		} catch (QueryException $qe) {
			$queryError = $qe->getMessage();
			$this->setError('Failed to save owner.', $queryError);
		}

		return $this->getModel();
	}

	public function assignUser(User $user)
	{
		try {
			$user->assignRole('owner');
			$owner = $this->save(['user_id' => $user->id]);

			$this->setModel($owner);

			$this->setSuccess('Successfully asssign user as owner.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to assign user owner.', $error);
		}

		return $this->getModel();
	}

	public function assignCompany(Company $company)
	{
		try {
			$owner = $this->getModel();
			$owner->company_id = $company->id;
			$owner->save();

			$this->setModel($owner);

			$this->setSuccess('Successfully assign company to owner.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to assign company to owner.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$owner = $this->getModel();
			$force ? $owner->forceDelete() : $owner->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete owner from company');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete owner from company', $error);
		}

		return $this->returnResponse();
	}

	public function restore()
	{
		try {
			$owner = $this->getModel();
			$owner->restore();

			$this->setModel($owner);

			$this->setSuccess('Successfully restored owner.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore owner.', $error);
		}

		return $this->getModel();
	}
}
