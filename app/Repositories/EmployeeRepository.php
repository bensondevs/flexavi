<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Http\Resources\EmployeeResource;

use App\Models\Employee;

use App\Repositories\Base\BaseRepository;

class EmployeeRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Employee);
	}

	public function inviteables(array $options = [])
	{
		array_push($options['wheres'], [
			'column' => 'user_id',
			'value' => null,
		]);

		return $this->all($options);
	}

	public function save(array $employeeData)
	{
		try {
			$employee = $this->getModel();
			if (isset($employeeData['photo']))
				$employee->photo = $employeeData['photo'];
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

		return $this->getModel();
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

		return $this->returnResponse();
	}
}
