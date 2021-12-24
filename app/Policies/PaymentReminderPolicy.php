<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\{ User, Appointment, PaymentReminder };

class PaymentReminderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any payment reminders');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentReminder  $paymentReminder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PaymentReminder $paymentReminder)
    {
        return $user->hasCompanyPermission($paymentReminder->company_id, 'view payment reminders');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'create payment reminders');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentReminder  $paymentReminder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PaymentReminder $paymentReminder)
    {
        if (! $user->hasCompanyPermission($paymentReminder->company_id, 'edit payment reminders')) {
            return abort(403, 'You have no permission to update payment reminder.');
        }

        if ($paymentReminder->company_id !== $appointment->company_id) {
            return abort(403, 'Cannot use other company\'s data');
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentReminder  $paymentReminder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PaymentReminder $paymentReminder)
    {
        return $user->hasCompanyPermission($paymentReminder->company_id, 'delete payment reminders');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentReminder  $paymentReminder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PaymentReminder $paymentReminder)
    {
        return $user->hasCompanyPermission($paymentReminder->company_id, 'restore payment reminders');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentReminder  $paymentReminder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PaymentReminder $paymentReminder)
    {
        return $user->hasCompanyPermission($paymentReminder->company_id, 'force delete payment reminders');
    }
}
