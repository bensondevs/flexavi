<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ 
	Employee,
	Appointment,
	AppointmentEmployee 
};

class AppointmentEmployeeRepository extends BaseRepository
{
	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new AppointmentEmployee);
	}

	/**
	 * Assign employee to appointment
	 * 
	 * @param  \App\Models\Appointment  $appointment
	 * @param  \App\Models\Employee  $employee
	 * @return \App\Models\AppointmentEmployee
	 */
	public function assignEmployee(Appointment $appointment, Employee $employee)
	{
		//
	}

	/**
	 * Unassign appointment employee.
	 * 
	 * @return bool
	 */
	public function unassignEmployee()
	{
		//
	}
}
