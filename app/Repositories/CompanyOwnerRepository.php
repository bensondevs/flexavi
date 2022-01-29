<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Owner, Company };

class CompanyOwnerRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Owner);
	}

	/**
	 * Get all inviteable owners
	 * 
	 * @param  array  $options
	 * @return \Illuminate\Support\Collection|
	 * 		   \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function inviteables(array $options = [])
	{
		array_push($options['wheres'], [
			'column' => 'user_id',
			'value' => null,
		]);

		return $this->all($options);
	}

	/**
	 * Save to create or update owner
	 * 
	 * @param  array  $ownerData
	 * @return \App\Models\Owner
	 */
	public function save(array $ownerData)
	{
		try {
			$owner = $this->getModel();
			$owner->fill($ownerData);
			$owner->save();

			$this->setModel($owner);

			$this->setSuccess('Successfully save owner.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save owner.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Assign user to owner
	 * 
	 * @param  \App\Models\User  $user
	 * @return \App\Models\Owner
	 */
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

	/**
	 * Assign company to owner
	 * 
	 * @param  \App\Models\Company  $company
	 * @return \App\Models\Owner
	 */
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

	/**
	 * Delete company
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
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

	/**
	 * Restore owner from soft-delete
	 * 
	 * @return \App\Models\Owner
	 */
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
