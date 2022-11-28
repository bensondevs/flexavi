<?php

namespace App\Repositories\Car;

use App\Enums\CarRegisterTimeEmployee\PassangerType;
use App\Models\{Car\CarRegisterTime, Car\CarRegisterTimeEmployee, Employee\Employee};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class CarRegisterTimeEmployeeRepository extends BaseRepository
{
    /**
     * Parent model
     *
     * @var CarRegisterTime|null
     */
    private $time;

    /**
     * Create New Repository Instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new CarRegisterTimeEmployee());
    }

    /**
     * Set parent model
     *
     * @param CarRegisterTime $time
     * @return void
     */
    public function setTime(CarRegisterTime $time)
    {
        $this->time = $time;
    }

    /**
     * Get parent model
     *
     * @return CarRegisterTime|null
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Assign employee to registered car time.
     *
     * @param Employee  $employee
     * @param array  $assignData
     * @return CarRegisterTimeEmployee|null
     */
    public function assignEmployee(Employee $employee, array $assignData)
    {
        try {
            $assignedEmployee = new CarRegisterTimeEmployee($assignData);
            $assignedEmployee->time = $this->getTime();
            $assignedEmployee->employee = $employee;
            if (
                !$this->getTime()
                    ->assignedEmployees()
                    ->exists()
            ) {
                $assignedEmployee->passanger_type = PassangerType::Driver;
            }
            $assignedEmployee->save();
            $this->setModel($assignedEmployee);
            $this->setSuccess(
                'Successfully assign employee to car register time.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to assign employee to car register times.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Set assigned employee to become the car driver.
     *
     * @return CarRegisterTimeEmployee|null
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
            $this->setSuccess(
                'Successfully set assigned employee to be driver.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to set assigned employee as driver',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Set assigned employee to out from car.
     *
     * @return CarRegisterTimeEmployee|null
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
     * @param bool $force
     * @return bool
     */
    public function unassignEmployee(bool $force = false)
    {
        try {
            $assignedEmployee = $this->getModel();
            $force
                ? $assignedEmployee->forceDelete()
                : $assignedEmployee->delete();
            $this->setSuccess(
                'Successfully unassign employee from car register time.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to unassign employee from car register time.',
                $error
            );
        }

        return $this->returnResponse();
    }
}
