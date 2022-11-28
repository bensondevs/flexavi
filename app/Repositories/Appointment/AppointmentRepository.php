<?php

namespace App\Repositories\Appointment;

use App\Enums\Appointment\AppointmentStatus as Status;
use App\Models\{Appointment\Appointment, Appointment\AppointmentEmployee, User\User, Work\Work};
use App\Repositories\Base\BaseRepository;
use App\Repositories\ExecuteWork\ExecuteWorkRepository;
use App\Repositories\Revenue\RevenueRepository;
use App\Repositories\Work\WorkRepository;
use Illuminate\Database\QueryException;

class AppointmentRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Appointment());
    }

    /**
     * Get unplanned appointments
     *
     * @param array  $options
     * @param bool  $pagination
     * @return array
     */
    public function unplanneds(array $options = [], bool $pagination = false)
    {
        $model = $this->getModel();
        $model = $model->whereDoesntHave('worklists');
        $this->setModel($model);

        return $this->all($options, $pagination);
    }

    /**
     * Create or update appointment
     *
     * @param array  $appointmentData
     * @return Appointment|null
     */
    public function save(array $appointmentData)
    {
        try {
            $appointment = $this->getModel();
            $appointment->fill($appointmentData);

            $appointment->save();

            $appointment->attachRelatedAppointments($appointmentData['related_appointment_ids']);

            $this->setModel($appointment);
            $this->setSuccess('Successfully save appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save appointment.', $error);
        }

        return $this->getModel();
    }

    /**
     * save appointment as draft
     *
     * @param array  $appointmentData
     * @return Appointment|null
     */
    public function draft(array $appointmentData)
    {
        try {
            $appointment = $this->getModel();
            $appointment->fill($appointmentData);
            $appointment->status = Status::Draft;
            $appointment->save();

            $appointment->attachRelatedAppointments($appointmentData['related_appointment_ids']);

            $this->setModel($appointment);
            $this->setSuccess('Successfully save appointment as draft.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save appointment as draft.', $error);
        }

        return $this->getModel();
    }

    /**
     * Execute appointment
     *
     * @return Appointment|null
     */
    public function execute()
    {
        try {
            $appointment = $this->getModel();
            $appointment->execute();
            $this->setModel($appointment);
            $this->setSuccess('Successfully execute appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to execute appointment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Create and attach work to appointment
     *
     * @param array  $workData
     * @return Appointment|null
     */
    public function addWork(array $workData)
    {
        try {
            $appointment = $this->getModel();
            $workRepository = new WorkRepository();
            $work = $workRepository->save($workData);
            $executeWorkRepository = new ExecuteWorkRepository();
            $executeWorkRepository->execute([
                'appointment_id' => $appointment->id,
                'work_id' => $work->id,
            ]);
            $this->setSuccess('Successfully add work to appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to add work to appointment');
        }

        return $this->getModel();
    }

    /**
     * Assign employee to appointment
     *
     * @param User $user
     * @return bool
     */
    public function assignEmployee(User $user)
    {
        try {
            AppointmentEmployee::create([
                'appointment_id' => $this->getModel()->id,
                'user_id' => $user->id,
            ]);
            $this->setSuccess('Successfully assign employee to appointment.');
        } catch (QueryException $qe) {
            $this->setError('Failed to assign employee to appointment.');
        }

        return $this->returnResponse();
    }

    /**
     * Unassign employee from appointment
     *
     * @param AppointmentEmployee $appointmentEmployee
     * @return bool
     */
    public function unassignEmployee(AppointmentEmployee $appointmentEmployee)
    {
        try {
            $appointmentEmployee->delete();
            $this->setSuccess('Successfully assign employee to appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign employee to appointment.');
        }

        return $this->returnResponse();
    }

    /**
     * Process appointment
     *
     * @return Appointment|null
     */
    public function process()
    {
        try {
            $appointment = $this->getModel();
            $appointment->process();
            $this->setModel($appointment);
            $this->setSuccess('Successfully process appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed process appointment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Cancel appointment
     *
     * @param array  $cancelData
     * @return Appointment|null
     */
    public function cancel(array $cancelData)
    {
        try {
            $appointment = $this->getModel();
            $appointment->cancellation_cause =
                $cancelData['cancellation_cause'];
            $appointment->cancellation_vault =
                $cancelData['cancellation_vault'];
            $appointment->cancellation_note = $cancelData['cancellation_note'];
            $appointment->cancel();
            $this->setModel($appointment);
            $this->setSuccess('Successfully cancel appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to cancel appointment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Reschedule cancelled appointment
     *
     * @param array  $rescheduleData
     * @return Appointment|null
     */
    public function reschedule(array $rescheduleData)
    {
        try {
            $appointment = $this->getModel();
            $appointment->reschedule($rescheduleData);
            $this->setModel($appointment);
            $this->setSuccess('Successfully reschedule appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to reschedule appointment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Move appointment from one appointmentable
     * to another appointmentable
     *
     * @param mixed  $appointmentable
     * @return Appointment|null
     */
    public function moveTo($appointmentable)
    {
        try {
            $appointment = $this->getModel();
            $type = get_class($appointmentable);
            $pivot = $appointment
                ->appointmentables()
                ->where('appointmentable_type', $type)
                ->first();
            if ($pivot) {
                return $pivot->delete();
            }
            $appointmentable
                ->appointment()
                ->attach($appointment, ['id' => generateUuid()]);
            $this->setSuccess(
                'Successfully move appointment to another ' .
                    get_lower_class($appointmentable)
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to move appointment', $error);
        }

        return $this->getModel();
    }

    /**
     * Sync revenues of the appointment
     *
     * @return Appointment|null
     */
    public function syncRevenues()
    {
        try {
            $appointment = $this->getModel();
            $revenues = [];
            foreach (Work::with('revenueable.revenue')
                ->finishedAt($appointment)
                ->get()
                as $work) {
                if (!($revenueable = $work->revenueable)) {
                    $revenueRepository = new RevenueRepository();
                    $revenue = $revenueRepository->recordWork($work);
                    $revenueable = $work->attachRevenue($revenue);
                    $revenues[$revenue->id] = ['id' => generateUuid()];
                    continue;
                }
                $revenue = $revenueable->revenue;
                $revenues[$revenue->id] = ['id' => generateUuid()];
            }
            $appointment->revenues()->sync($revenues);
            $this->getModel();
            $this->setSuccess(
                'Successfully syncronize work revenues to appointment.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to syncronize work revenues to appointment.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Delete appointment
     *
     * @param bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $appointment = $this->getModel();
            $force ? $appointment->forceDelete() : $appointment->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete appointment.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore appointment
     *
     * @return Appointment|null
     */
    public function restore()
    {
        try {
            $appointment = $this->getModel();
            $appointment->restore();
            $this->setModel($appointment);
            $this->setSuccess('Successfully restore appointment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore appointment.', $error);
        }

        return $this->getModel();
    }
}
