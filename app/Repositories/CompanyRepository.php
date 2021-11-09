<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Company };

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
		foreach ($owners as $owner) {
			array_push($ownerIds, $owner->id);
		}

		$companies = Company::whereIn('owner_id', $ownerIds)->get();
		return $this->setCollection($companies);
	}

	public function uploadCompanyLogo($logoFile)
	{
		try {
			$company = $this->getModel();
			$company->company_logo = $logoFile;
			$company->save();

			$this->setModel($company);

			$this->setSuccess('Successfully upload company logo');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to upload company logo.', $error);
		}

		return $this->getModel();
	}

	public function save(array $companyData)
	{
		try {
			$company = $this->getModel();
			$company->fill($companyData);
			$company->save();
			$company->invoicing_address = $companyData['invoicing_address'];
			$company->visiting_address = $companyData['visiting_address'];

			$this->setModel($company);

			$this->setSuccess('Successfully save company data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save company data.', $error);
		}

		return $this->getModel();
	}

	public function delete($force = false)
	{
		try {
			$company = $this->getModel();
			$force ? $company->forceDelete() : $company->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete company');
		} catch (QueryException $qe) {
			$this->setError('Failed to delete company');
		}

		return $this->returnResponse();
	}
}
