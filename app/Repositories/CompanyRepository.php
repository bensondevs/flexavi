<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Company };

class CompanyRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Company);
	}

	/**
	 * Upload company logo
	 * 
	 * @param  mixed  $logoFile
	 * @return \App\Models\Company
	 */
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

	/**
	 * Save company for creating or updating company
	 * 
	 * @param  array  $companyData
	 * @return \App\Models\Company
	 */
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

	/**
	 * Delete company
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$company = $this->getModel();
			$force ? $company->forceDelete() : $company->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete company');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete company', $error);
		}

		return $this->returnResponse();
	}
}
