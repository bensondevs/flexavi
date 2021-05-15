<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

use App\Enums\AppointmentableType;

use App\Models\Appointment;
use App\Models\Appointmentable;

use App\Repositories\Base\BaseRepository;

class AppointmentRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setModel(new Appointment);
	}

	public function companyAppointments(Company $company)
	{
		$appointments = $company->appointments;
		return $this->setCollection($appointments);
	}

	public function save(array $appointmentData)
	{
		try {
			$appointment = $this->getModel();
			$appointment->fill($appointmentData);
			$appointment->save();

			$this->setModel($appointment);

			$this->setSuccess('Successfully save appointment');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save appointment.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function assignType(array $assignData)
	{
		try {
			$appointment = $this->getModel();
			$appointment->pivot()->save([
				'appointment_id' => $appointment->id,
				'appointmentable_id' => $assignData['id'],
				'appointmentable_type' => AppointmentableType::fromKey($assignData['type']),
			]);

			$this->setModel($appointment);

			$this->setSuccess('Successfully assign item to appointment');
		} catch (QueryException $qe) {
			$this->setError('Failed to assign item to appointment');
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$appointment = $this->getModel();
			$force ?
				$appointment->forceDelete() :
				$appointment->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete appointment.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete appointment.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
