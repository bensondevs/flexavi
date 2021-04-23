<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Company; 

class CompanyRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Company);
	}

	public function save(array $companyData)
	{
		try {
			$company = $this->getModel();
			$company->fill($companyData);
			$company->save();

			$this->setModel($company);

			$this->setSuccess('Successfully save company data.');
		} catch (QueryException $qe) {
			$this->setError('Failed to save company data.', $qe->getModel());
		}

		return $this->getModel();
	}

	public function delete($force = false)
	{
		
	}
}
