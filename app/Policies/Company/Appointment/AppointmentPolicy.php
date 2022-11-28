<?php

namespace App\Policies\Company\Appointment;

use App\Enums\Appointment\AppointmentStatus;
use App\Models\Appointment\Appointment;
use App\Models\Appointment\AppointmentEmployee;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, $appointmentable = null): bool
    {
        if ($appointmentable !== null) {
            return $user->hasCompanyDirectPermission($appointmentable->company_id, 'view any appointments');
        }


        return $user->hasDirectPermissionTwo('view any appointments');
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'view appointments');
    }

    public function create(User $user)
    {
        return $user->hasDirectPermissionTwo('create appointments');
    }

    public function assignEmployee(User $user, Appointment $appointment, User $employee)
    {
        if (AppointmentEmployee::isExists($appointment, $employee)) {
            return abort(422, 'Employee already assigned.');
        }

        if (!$user->hasCompanyDirectPermission($appointment->company_id, 'assign appointments employees')) {
            return abort(403, 'You don\'t have permission to assign employee to this appointment.');
        }

        $employee = $employee->user_role == "owner" ? $employee->owner : $employee->employee;
        if ($appointment->company_id !== $employee->company_id) {
            return abort(403, 'Cannot use this employee.');
        }

        return true;
    }

    public function unassignEmployee(User $user, AppointmentEmployee $appointmentEmployee)
    {
        $appointment = $appointmentEmployee->appointment;
        return $user->hasCompanyDirectPermission($appointment->company_id, 'unassign appointments employees');
    }

    public function generateInvoice(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'generate invoice appointments');
    }

    public function reschedule(User $user, Appointment $appointment)
    {

        if ($appointment->status == AppointmentStatus::Calculated) {
            return abort(422, 'This appointment cannot be rescheduled');
        }
        return $user->hasCompanyDirectPermission($appointment->company_id, 'reschedule appointments');
    }

    public function update(User $user, Appointment $appointment)
    {
        if ($appointment->status > AppointmentStatus::Created) {
            return abort(422, 'This appointment can no longer be edited.');
        }

        return $user->hasCompanyDirectPermission($appointment->company_id, 'edit appointments');
    }

    public function cancel(User $user, Appointment $appointment)
    {
        if ($appointment->status > AppointmentStatus::Created) {
            return abort(422, 'This appointment can no longer be cancelled.');
        }

        return $user->hasCompanyDirectPermission($appointment->company_id, 'cancel appointments');
    }

    public function execute(User $user, Appointment $appointment)
    {
        if ($appointment->status > AppointmentStatus::Created) {
            $currentStatus = AppointmentStatus::getValue($appointment->status);
            return $this->deny('You cannot this appointment. This appointment is already ' . $currentStatus);
        }

        return $user->hasCompanyDirectPermission($appointment->company_id, 'execute appointments');
    }

    public function process(User $user, Appointment $appointment)
    {
        if ($appointment->status !== AppointmentStatus::InProcess) {
            return $this->deny('You can only process appointment that has been in process only');
        }

        return $user->hasCompanyDirectPermission($appointment->company_id, 'process appointments');
    }

    public function calculate(User $user, Appointment $appointment)
    {
        if ($appointment->status !== AppointmentStatus::Processed) {
            return $this->deny('You can only calculate processed appointment.');
        }

        if ($appointment->hasActiveWorks()) {
            return $this->deny('This appointment has works that needs to be marked finish or unfinished.');
        }

        return $user->hasCompanyDirectPermission($appointment->company_id, 'calculate appointments');
    }

    public function delete(User $user, Appointment $appointment)
    {
        if (!$owner = $user->owner) {
            return $this->deny('Only owner that can delete appointment');
        }

        return $user->hasCompanyDirectPermission($appointment->company_id, 'delete appointments');
    }

    public function restore(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'restore appointments');
    }

    public function forceDelete(User $user, Appointment $appointment)
    {
        if (!$user->hasRole('admin')) {
            return $this->deny('Only administrator that has this permission.');
        }

        return true;
    }
}
