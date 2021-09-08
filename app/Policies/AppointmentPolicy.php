<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Appointment;

use App\Enums\Appointment\AppointmentStatus;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, $appointmentable = null)
    {
        if ($appointmentable !== null) {
            return $user->hasCompanyPermission($appointmentable->company_id, 'view any appointments');
        }

        return $user->hasPermissionTo('view any appointments');
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'view appointments');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create appointments');
    }

    public function generateInvoice(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission('generate invoice appointments');
    }

    public function reschedule(User $user, Appointment $appointment)
    {
        if ($appointment->status !== AppointmentStatus::Calculated) {
            return abort(422, 'This appointment cannot be rescheduled');
        }

        return $user->hasCompanyPermission($appointment->company_id, 'reschedule appointments');
    }

    public function update(User $user, Appointment $appointment)
    {
        if ($appointment->status > AppointmentStatus::Created) {
            return abort(422, 'This appointment can no longer be edited.');
        }

        return $user->hasCompanyPermission($appointment->company_id, 'edit appointments');
    }

    public function cancel(User $user, Appointment $appointment)
    {
        if ($appointment->status > AppointmentStatus::Created) {
            return abort(422, 'This appointment can no longer be cancelled.');
        }

        return $user->hasCompanyPermission($appointment->company_id, 'cancel appointments');
    }

    public function execute(User $user, Appointment $appointment)
    {
        if ($appointment->status > AppointmentStatus::Created) {
            $currentStatus = AppointmentStatus::getValue($appointment->status);
            return $this->deny('You cannot this appointment. This appointment is already ' . $currentStatus);
        }

        return $user->hasCompanyPermission($appointment->company_id, 'execute appointments');
    }

    public function process(User $user, Appointment $appointment)
    {
        if ($appointment->status !== AppointmentStatus::InProcess) {
            return $this->deny('You can only process appointment that has been in process only');
        }

        return $user->hasCompanyPermission($appointment->company_id, 'process appointments');
    }

    public function calculate(User $user, Appointment $appointment) 
    {
        if ($appointment->status !== AppointmentStatus::Processed) {
            return $this->deny('You can only calculate processed appointment.');
        }

        if ($appointment->hasActiveWorks()) {
            return $this->deny('This appointment has works that needs to be marked finish or unfinished.');
        }

        return $user->hasCompanyPermission($appointment->company_id, 'calculate appointments');
    }

    public function delete(User $user, Appointment $appointment)
    {
        if (! $owner = $user->owner) {
            return $this->deny('Only owner that can delete appointment');
        }

        return $user->hasCompanyPermission($appointment->company_id, 'delete appointments');
    }

    public function restore(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'restore appointments');
    }

    public function forceDelete(User $user, Appointment $appointment)
    {
        if (! $user->hasRole('admin')) {
            return $this->deny('Only administrator that has this permission.');
        }

        return true;
    }
}
