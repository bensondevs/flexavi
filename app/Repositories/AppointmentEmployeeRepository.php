<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\AppointmentEmployee;

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

	public function assignEmployee(Appointment $appointment, Employee $employee)
	{
		//
	}

	public function unassignEmployee()
	{
		//
	}

	public function restore()
	{
		//
	}
}
