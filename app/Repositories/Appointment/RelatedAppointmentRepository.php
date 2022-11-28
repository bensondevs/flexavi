<?php

namespace App\Repositories\Appointment;

use App\Models\Appointment\RelatedAppointment;
use App\Repositories\Base\BaseRepository;

class RelatedAppointmentRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new RelatedAppointment);
	}
}
