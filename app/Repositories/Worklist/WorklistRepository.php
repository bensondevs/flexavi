<?php

namespace App\Repositories\Worklist;

use App\Models\{Appointment\Appointment,
    Appointment\Appointmentable,
    Car\Car,
    Worklist\Worklist,
    Worklist\WorklistCar,
    Worklist\WorklistEmployee};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WorklistRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Worklist());
    }

    /**
     * Save worklist from supplied array input
     *
     * @param  array  $worklistData
     * @return Worklist|null
     */
    public function save(array $worklistData = [])
    {
        try {
            $worklist = $this->getModel();
            $worklist->fill($worklistData);
            $worklist->save();
            $this->setModel($worklist);
            $this->setSuccess('Successfully save worklist.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save worklist.', $error);
        }

        return $this->getModel();
    }

    /**
     * Save worklist sorting route status
     *
     * @param  array  $worklistData
     * @return Worklist|null
     */
    public function saveSortingRoute(array $worklistData = [])
    {
        try {
            $worklist = $this->getModel();
            $worklist->fill($worklistData);
            $worklist->save();
            $this->setModel($worklist);
            $this->setSuccess(
                'Successfully save worklist sorting route status.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to save worklist sorting route status.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Attach appointment to current worklist
     *
     * @param  Appointment $appointment
     * @return Worklist|null
     */
    public function attachAppointment(Appointment $appointment)
    {
        try {
            $worklist = $this->getModel();
            Appointmentable::create([
                'company_id' => $appointment->company_id,
                'appointmentable_id' => $worklist->id,
                'appointmentable_type' => Worklist::class,
                'appointment_id' => $appointment->id,
            ]);
            $this->setModel($worklist);
            $this->setSuccess('Successfully attach appointment to worklist.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to attach appointment to worklist', $error);
        }

        return $this->getModel();
    }

    /**
     * Attach multiple appointments
     *
     * @param  array  $appointmentIds
     * @return Worklist|null
     */
    public function attachManyAppointments(array $appointmentIds)
    {
        try {
            $worklist = $this->getModel();
            Appointmentable::attachMany($worklist, $appointmentIds);
            $this->setModel($worklist);
            $this->setSuccess(
                'Successfully attach many appointments to worklist.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to attach many appointment to worklist.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Attach multiple appointments
     *
     * @param  mixed  $fromWorklist
     * @param  mixed  $toWorklist
     * @param  Appointment  $appointment
     * @return Worklist|null
     */
    public function moveAppointment(
        $fromWorklist,
        $toWorklist,
        Appointment $appointment
    ) {
        try {
            if (!is_null($fromWorklist)) {
                $fromWorklist->appointments()->detach($appointment);
                $fromWorklist->save();
            }
            if (!is_null($toWorklist)) {
                Appointmentable::create([
                    'company_id' => $appointment->company_id,
                    'appointmentable_id' => $toWorklist->id,
                    'appointmentable_type' => Worklist::class,
                    'appointment_id' => $appointment->id,
                ]);
            } else {
                $workday = $fromWorklist->workday;
                $workday->appointments()->attach($appointment, ['id' => generateUuid(), 'company_id' => $workday->company_id]);
            }
            $this->setSuccess('Successfully move appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to move appointment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Detach appointment from worklist
     *
     * @param  Appointment  $appointment
     * @return Worklist|null
     */
    public function detachAppointment(Appointment $appointment)
    {
        try {
            $worklist = $this->getModel();
            $worklist->appointments()->detach($appointment);
            $workday = $worklist->workday;
            Appointmentable::attachMany($workday, [$appointment->id]);
            $worklist->save();
            $this->setModel($worklist);
            $this->setSuccess('Successfully detach appointment from worklist.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to detach appointment from worklist.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Unassign car from worklist
     *
     * @return Worklist|null
     */
    public function unassignCar()
    {
        try {
            $worklist = $this->getModel();
            WorklistCar::where('worklist_id', $worklist->id)->delete();
            $this->setModel($worklist);
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign car to worklist.', $error);
        }

        return $this->getModel();
    }

    /**
     * Assign car to worklist
     *
     * @param  Car  $car
     * @return Worklist|null
     */
    public function assignCar(Car $car)
    {
        try {
            $worklist = $this->getModel();
            WorklistCar::assignCar($worklist, $car);
            $worklist->save();
            $this->setModel($worklist);
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign car to worklist.', $error);
        }

        return $this->getModel();
    }

    /**
     * Unassign employees to worklist
     *
     * @return Worklist|null
     */
    public function unassignEmployees()
    {
        try {
            $worklist = $this->getModel();
            WorklistEmployee::where('worklist_id', $worklist->id)->delete();
            return $this->setModel($worklist);
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign employees to worklist.' . $error);
        }

        return $this->getModel();
    }
    /**
     * Assign employees to worklist
     *
     * @param  array  $employeeIds
     * @return Worklist|null
     */
    public function assignEmployees(array $employeeIds)
    {
        try {
            $worklist = $this->getModel();
            WorklistEmployee::attachMany($worklist, $employeeIds);
            return $this->setModel($worklist);
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign employees to worklist.' . $error);
        }

        return $this->getModel();
    }

    /**
     * Detach many appointment from worklist
     *
     * @param  array  $appointmentIds
     * @return Worklist|null
     */
    public function detachManyAppointments(array $appointmentIds)
    {
        try {
            $worklist = $this->getModel();
            $worklist->appointments()->detach($appointmentIds);
            $worklist->save();
            $this->setModel($worklist);
            $this->setSuccess(
                'Successfully detach many appointments from worklist.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to detach many appointments from worklist.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Delete all attached appointments of worklist
     *
     * @return Worklist|null
     */
    public function truncateAppointments()
    {
        try {
            $worklist = $this->getModel();
            $worklist->appointments()->detach();
            $worklist->save();
            $this->setModel($worklist);
            $this->setSuccess(
                'Successfully truncate appointments inside worklist.'
            );
        } catch (QueryException $qe) {
            $this->setError('Failed to truncate appointments inside worklist.');
        }

        return $this->getModel();
    }

    /**
     * Process worklist
     *
     * @return Worklist|null
     */
    public function process()
    {
        try {
            $worklist = $this->getModel();
            $worklist->process();
            $this->setModel($worklist);
            $this->setSuccess('Successfully process worklist.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to process worklist.', $error);
        }

        return $this->getModel();
    }

    /**
     * Calculate worklist revenue and cost
     *
     * @return Worklist|null
     */
    public function calculate()
    {
        try {
            $worklist = $this->getModel();
            $worklist->calculate();
            $this->setModel($worklist);
            $this->setSuccess('Successfully calculate worklist.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to calculate worklist.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete worklist
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $worklist = $this->getModel();
            $appointmentIds = $worklist->appointments("appointment_id")->pluck("appointment_id")->toArray();

            // move appointments to unplanned appointments
            $worklist->appointments()->sync([]);

            $workday = $worklist->workday;
            // get the existing appointments in workday before deleting appointments in workday
            $workdayAppointmentIds = $workday->appointments()->get()->pluck("id")->toArray() ?? [];
            // delete current workday appointments
            $workday->appointments()->sync([]);
            // and then reattach workday appointments
            $workdayAppointmentIds = array_merge($workdayAppointmentIds, $appointmentIds);
            Appointmentable::attachMany($workday, $workdayAppointmentIds);

            $force ? $worklist->forceDelete() : $worklist->delete();

            $this->destroyModel();
            $this->setSuccess('Successfully delete worklist.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete worklist.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore soft deleted worklist
     *
     * @return Worklist|null
     */
    public function restore()
    {
        try {
            $worklist = $this->getModel();
            $worklist->restore();
            $this->setModel($worklist);
            $this->setSuccess('Successfully restore worklist.');
        } catch (QueryException $e) {
            $error = $e->getMessage();
            $this->setError('Failed to restore worklist.', $error);
        }

        return $this->getModel();
    }
}
