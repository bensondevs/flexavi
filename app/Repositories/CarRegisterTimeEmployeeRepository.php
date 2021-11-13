<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Enums\CarRegisterTimeEmployee\PassangerType;

use App\Models\{
	Employee,
	CarRegisterTime as Time,
	CarRegisterTimeEmployee as AssignedEmployee
};

class CarRegisterTimeEmployeeRepository extends BaseRepository
{
	/**
     * Parent model
     * 
     * \App\Models\CarRegisterTime  $time 
     */
	private $time;

	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new AssignedEmployee);
	}

	/**
     * Set parent model
     * 
     * @param \App\Models\CarRegisterTime  $time 
     * @return  void
     */
	public function setTime(Time $time)
	{
		$this->time = $time;
	}

	/**
     * Get parent model
     *  
     * @return  void
     */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * Assign employee to registered car time.
	 * 
	 * @param \App\Models\Employee  $employee
	 * @param array  $assignData
	 * @return \App\Models\CarRegisterTimeEmployee  mixed
	 */
	public function assignEmployee(Employee $employee, array $assignData)
	{
		try {
			$assignedEmployee = new AssignedEmployee($assignData);
			$assignedEmployee->time = $this->getTime();
			$assignedEmployee->employee = $employee;

			if (! $this->getTime()->assignedEmployees()->exists()) {
				$assignedEmployee->passanger_type = PassangerType::Driver;
			}

			$assignedEmployee->save();

			$this->setModel($assignedEmployee);

			$this->setSuccess('Successfully assign employee to car register time.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to assign employee to car register times.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set assigned employee to become the car driver.
	 * 
	 * @return \App\Models\CarRegisterTimeEmployee  mixed
	 */
	public function setAsDriver()
	{
		try {
			$assignedEmployee = $this->getModel();

			$time = $assignedEmployee->carRegisterTime;
			if ($currentDriver = $time->currentDriver()) {
				$currentDriver->setAsPassanger();
			}

			$assignedEmployee->setAsDriver();

			$this->setModel($assignedEmployee);

			$this->setSuccess('Successfully set assigned employee to be driver.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set assigned employee as driver', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set assigned employee to out from car.
	 * 
	 * @return \App\Models\CarRegisterTimeEmployee  mixed
	 */
	public function setOut()
	{
		try {
			$assignedEmployee = $this->getModel();
			$assignedEmployee->out_time = now();
			$assignedEmployee->save();

			$this->setModel($assignedEmployee);

			$this->setSuccess('Successfully set out employee from the car.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set out employee from car.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Assign employee to registered car time.
	 * 
	 * @param \App\Models\CarRegisterTimeEmployee  $assignedEmployee
	 * @return array  mixed
	 */
	public function unassignEmployee(bool $force = false)
	{
		try {
			$assignedEmployee = $this->getModel();
			$force ?
				$assignedEmployee->forceDelete() :
				$assignedEmployee->delete();

			$this->setSuccess('Successfully unassign employee from car register time.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to unassign employee from car register time.', $error);
		}

		return $this->returnResponse();
	}
}
