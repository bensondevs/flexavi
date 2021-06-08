<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

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
			$this->setError('Failed to save owner.');
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
			$this->setError(
				'Failed to assign company to owner.', 
				$qe->getMessage()
			);
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
			$this->setError(
				'Failed to delete owner from company', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
