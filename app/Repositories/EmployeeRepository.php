<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Employee;

use App\Repositories\Base\BaseRepository;

class EmployeeRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Employee);
	}

	public function ofCompany($companyId)
	{
		$employees = $this->getModel()
			->where('company_id', $companyId)
			->get();

		$this->setCollection($employees);

		return $this->getCollection();
	}

	public function save(array $employeeData)
	{
		try {
			$employee = $this->getModel();
			$employee->fill($employeeData);
			$employee->save();

			$this->setModel($employee);

			$this->setSuccess('Successfully save employee data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save employee data.', 
				$qe->getMessage()
			);
		}
	}

	public function delete($force = false)
	{
		try {
			$employee = $this->getModel();

			$force ?
				$employee->forceDelete() :
				$employee->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete employee.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete employee.', 
				$qe->getMessage()
			);
		}

		return $this->returResponse();
	}
}
