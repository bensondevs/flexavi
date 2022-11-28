<?php

namespace App\Observers;

use App\Jobs\Workday\CreateWorkdayByAppointmentJob;
use App\Models\{Appointment\Appointment};
use App\Services\Log\LogService;

class AppointmentObserver
{
    /**
     * Handle the Appointment "creating" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function creating(Appointment $appointment)
    {
        $appointment->id = generateUuid();
    }

    /**
     * Handle the Appointment "created" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function created(Appointment $appointment)
    {
        dispatch(new CreateWorkdayByAppointmentJob(
            $appointment->company,
            $appointment
        ));

        $appointment->syncWorkdays();

        if ($user = auth()->user())
            LogService::make("appointment.store")->by($user)->on($appointment)->write();
    }

    /**
     * Handle the Appointment "updated" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        if ($appointment->isDirty('start') || $appointment->isDirty('end')) {
            dispatch(new CreateWorkdayByAppointmentJob(
                $appointment->company,
                $appointment
            ));
            $appointment->syncWorkdays();
        }

        if ($user = auth()->user()) {
            if ($appointment->isDirty("start"))
                LogService::make("appointment.updates.start")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("end"))
                LogService::make("appointment.updates.end")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("include_weekend"))
                LogService::make("appointment.updates.include_weekend")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("previous_appointment_id"))
                LogService::make("appointment.updates.previous_appointment_id")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("next_appointment_id"))
                LogService::make("appointment.updates.next_appointment_id")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("description"))
                LogService::make("appointment.updates.description")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("cancellation_cause"))
                LogService::make("appointment.updates.cancellation_cause")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("cancellation_vault"))
                LogService::make("appointment.updates.cancellation_vaults.{$appointment->cancellation_vault}")
                    ->by($user)->on($appointment)->write();

            if ($appointment->isDirty("cancellation_note"))
                LogService::make("appointment.updates.cancellation_note")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("description"))
                LogService::make("appointment.updates.description")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("status"))
                LogService::make("appointment.updates.statuses.{$appointment->status}")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("type"))
                LogService::make("appointment.updates.types.{$appointment->type}")->by($user)->on($appointment)->write();

            if ($appointment->isDirty("note"))
                LogService::make("appointment.updates.note")->by($user)->on($appointment)->write();
        }
    }

    /**
     * Handle the Appointment "executed" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function executed(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "processed" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function processed(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "cancelled" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function cancelled(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "deleted" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        if ($user = auth()->user())
            LogService::make("appointment.delete")->by($user)->on($appointment)->write();
    }

    /**
     * Handle the Appointment "restored" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        if ($user = auth()->user())
            LogService::make("appointment.restore")->by($user)->on($appointment)->write();
    }

    /**
     * Handle the Appointment "force deleted" event.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        if ($user = auth()->user())
            LogService::make("appointment.force_delete")->by($user)->on($appointment)->write();
    }
}
