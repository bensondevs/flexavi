<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Repositories\CompanyOwnerRepository;

use App\Models\User;
use App\Models\Company; 

class CompanyRepository extends BaseRepository
{
	private $owner;

	public function __construct()
	{
		$this->setInitModel(new Company);
		$this->owner = new CompanyOwnerRepository();
	}

	public function ofUser(User $user)
	{
		$owners = $user->owners;
		$ownerIds = [];
		foreach ($owners as $owner) 
			array_push($ownerIds, $owner->id);

		$companies = Company::whereIn('owner_id', $ownerIds)->get();
		return $this->setCollection($companies);
	}

	public function save(array $companyData)
	{
		try {
			$company = $this->getModel();
			$company->fill($companyData);
			$company->visiting_address = $companyData['visiting_address'];
			$company->invoicing_address = $companyData['invoicing_address'];
			$company->save();

			$this->setModel($company);

			$this->setSuccess('Successfully save company data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save company data.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function register(array $registerData)
	{
		try {
			$company = $this->getModel();
			$company = $this->save($registerData['company']);
			$company->owner = $this->owner->save($registerData['owner']);

			$this->setModel($company);

			$this->setSuccess('Successfully register a company.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to register a company.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete($force = false)
	{
		try {
			$company = $this->getModel();
			$force ?
				$company->forceDelete() :
				$company->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete company');
		} catch (QueryException $qe) {
			$this->setError('Failed to delete company');
		}

		return $this->returnResponse();
	}
}
