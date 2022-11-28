<?php

namespace App\Repositories\Appointment;

use App\Models\{Appointment\Appointment, Appointment\AppointmentEmployee, Employee\Employee};
use App\Repositories\Base\BaseRepository;

class AppointmentEmployeeRepository extends BaseRepository
{
    /**
     * Create New Repository Instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new AppointmentEmployee());
    }

    /**
     * Assign employee to appointment
     *
     * @param  Appointment  $appointment
     * @param  Employee  $employee
     * @return void
     */
    public function assignEmployee(Appointment $appointment, Employee $employee)
    {
        // TODO: complete assignEmployee logic
    }

    /**
     * Unassign appointment employee.
     *
     * @return void
     */
    public function unassignEmployee()
    {
        // TODO: complete unassignEmployee logic
    }
}
