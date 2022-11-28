<?php

namespace App\Policies\Company\PaymentPickup;

use App\Models\{Appointment\Appointment, Employee\Employee, PaymentPickup\PaymentPickup, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPickupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any payment pickups');
    }

    /**
     * Determine whether the user can view any models related to an appointment.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\Appointment  $appointment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAppointment(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'view any payment pickups');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PaymentPickup $paymentPickup)
    {
        return $user->hasCompanyDirectPermission($paymentPickup->company_id, 'view payment pickups');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\Appointment  $appointment
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'create payment pickups');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(
        User $user,
        PaymentPickup $paymentPickup,
        Appointment $appointment,
        Employee $employee
    ) {
        if (!$user->hasCompanyDirectPermission($paymentPickup->company_id, 'edit payment pickups')) {
            abort(403, 'You have no permission to edit this payment pickup.');
        }

        if ($paymentPickup->company_id !== $appointment->company_id) {
            abort(403, 'Cannot use other company\'s appointment.');
        }

        if ($appointment->company_id !== $employee->company_id) {
            abort(403, 'Cannot use other company\'s employee.');
        }

        return true;
    }

    /**
     * Determine whether the user can pickup the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function pickup(User $user, PaymentPickup $paymentPickup)
    {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'pickup payment pickups';
        return $user->hasCompanyDirectPermission($companyId, $permissionName);
    }

    /**
     * Determine whether the user can add pickupable to the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @param  mixed  $pickupable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addPickupable(User $user, PaymentPickup $paymentPickup, $pickupable)
    {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'add pickupable payment pickups';
        if (!$user->hasCompanyDirectPermission($companyId, $permissionName)) {
            abort(403, 'You have no permission to add pickupable to this payment pickup');
        }

        if ($paymentPickup->company_id !== $pickupable->company_id) {
            abort(403, 'Cannot use pickupable from other company');
        }

        return true;
    }

    /**
     * Determine whether the user can add multiple pickupables to the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @param  array  $pickupables
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addMultiplePickupables(
        User $user,
        PaymentPickup $paymentPickup,
        array $pickupables
    ) {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'add multiple pickupables payment pickups';

        if (!$user->hasCompanyDirectPermission($companyId, $permissionName)) {
            abort(403, 'You have no permission to add multiple pickupables.');
        }

        foreach ($pickupables as $pickupable) {
            if ($pickupable->company_id !== $paymentPickup->company_id) {
                abort('Cannot use data from other company.');
            }
        }

        return true;
    }

    /**
     * Determine whether the user can remove pickupable from the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @param  mixed  $pickupable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function removePickupable(
        User $user,
        PaymentPickup $paymentPickup,
        $pickupable
    ) {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'remove pickupable payment pickups';
        if (!$user->hasCompanyDirectPermission($companyId, $permissionName)) {
            abort(403, 'You have no permission to remove pickupable.');
        }

        if ($paymentPickup->company_id !== $pickupable->company_id) {
            abort(403, 'Cannot use other company\'s data.');
        }

        return true;
    }

    /**
     * Determine whether the user can remove multiple pickupables from the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @param  array  $pickupables
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function removeMultiplePickupables(
        User $user,
        PaymentPickup $paymentPickup,
        array $pickupables
    ) {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'remove multiple pickupables payment pickups';

        if (!$user->hasCompanyDirectPermission($companyId, $permissionName)) {
            abort(403, 'You have no permission to remove multiple pickupables.');
        }

        foreach ($pickupables as $pickupable) {
            if ($pickupable->company_id !== $paymentPickup->company_id) {
                abort('Cannot use data from other company.');
            }
        }

        return true;
    }

    /**
     * Determine whether the user can truncate pickupables of the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function truncatePickupables(User $user, PaymentPickup $paymentPickup)
    {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'truncate pickupables payment pickups';

        if (!$user->hasCompanyDirectPermission($companyId, $permissionName)) {
            abort(403, 'You have no permission to truncate payment pickupables.');
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PaymentPickup $paymentPickup)
    {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'delete payment pickups';
        return $user->hasCompanyDirectPermission($companyId, $permissionName);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PaymentPickup $paymentPickup)
    {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'restore payment pickups';
        return $user->hasCompanyDirectPermission($companyId, $permissionName);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentPickup  $paymentPickup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PaymentPickup $paymentPickup)
    {
        $companyId = $paymentPickup->company_id;
        $permissionName = 'force delete payment pickups';
        return $user->hasCompanyDirectPermission($companyId, $permissionName);
    }
}
