<?php

namespace App\Policies;
use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Customer;
use App\Models\Quotation;

use App\Enums\Quotation\QuotationStatus;

class QuotationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any quotations.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view quotations');
    }

    /**
     * Determine whether the user can view any customer quotations.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer $customer
     * @return bool
     */
    public function viewAnyCustomer(User $user, Customer $customer)
    {
        return $user->hasCompanyPermission($customer->company_id, 'view quotations');
    }

    /**
     * Determine whether the user can view quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function view(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'view quotations');
    }

    /**
     * Determine whether the user can create quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer $quotation
     * @return bool
     */
    public function create(User $user, Customer $customer)
    {
        return $user->hasCompanyPermission($customer->company_id, 'create quotations');
    }

    /**
     * Determine whether the user can create quotation with appointment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer $customer
     * @param  \App\Models\Appointment $appointment
     * @return bool
     */
    public function createWithAppointment(User $user, Customer $customer, Appointment $appointment)
    {
        if ($customer->company_id != $appointment->company_id) {
            return abort(403, 'Cannot create quotation using other company data.');
        }

        if (! $user->hasCompanyPermission($customer->company_id, 'create quotations')) {
            return abort(403, 'You don\'t have permission to create quotation.');
        }

        return true;
    }

    /**
     * Determine whether the user can view quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer $customer
     * @return bool
     */
    public function update(User $user, Quotation $quotation, Customer $customer)
    {
        if ($quotation->company_id != $customer->company_id) {
            return abort(403, 'Cannot create quotation using other company data.');
        }

        if (! $user->hasCompanyPermission($quotation->company_id, 'edit quotations')) {
            return abort(403, 'You don\'t have permission to update quotation');
        }

        return true;
    }

    /**
     * Determine whether the user can update quotation with appointment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @param  \App\Models\Customer $customer
     * @param  \App\Models\Appointment $appointment
     * @return bool
     */
    public function updateWithAppointment(User $user, Quotation $quotation, Customer $customer, Appointment $appointment)
    {
        if ($quotation->company_id != $customer->company_id) {
            return abort(403, 'Cannot create quotation using other company data.');
        }

        if ($customer->company_id != $appointment->company_id) {
            return abort(403, 'Cannot create quotation using other company data.');
        }

        if (! $user->hasCompanyPermission($appointment->company_id, 'edit quotations')) {
            return abort(403, 'You don\'t have permission to update quotation');
        }

        return true;
    }

    /**
     * Determine whether the user can send quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function send(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'send quotations');
    }

    /**
     * Determine whether the user can send quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function print(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'print quotations');
    }

    /**
     * Determine whether the user can revise quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function revise(User $user, Quotation $quotation)
    {
        if ($quotation->status >= ((string) QuotationStatus::Honored)) {
            $currentStatus = QuotationStatus::getDescription($quotation->status);
            return abort(403, 'Quotation has been ' . $currentStatus . ', can no longer be revised');
        }

        return $user->hasCompanyPermission($quotation->company_id, 'revise quotations');
    }

    /**
     * Determine whether the user can honor quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function honor(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'honor quotations');
    }

    /**
     * Determine whether the user can cancel quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function cancel(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'cancel quotations');
    }

    /**
     * Determine whether the user can generate invoice from quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function generateInvoice(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'generate invoice quotations');
    }

    /**
     * Determine whether the user can delete quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function delete(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'delete quotations');
    }

    /**
     * Determine whether the user can restore quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function restore(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'restore quotations');
    }

    /**
     * Determine whether the user can force delete quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function forceDelete(User $user, Quotation $quotation)
    {
        if ($quotation->status >= ((string) QuotationStatus::Sent)) {
            return abort(403, 'Force delete is not possible in this stage.');
        }

        return $user->hasCompanyPermission($quotation->company_id, 'force delete quotations');
    }

    /**
     * Determine whether the user can add attachment to quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function addAttachment(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'add quotation attachments');
    }

    /**
     * Determine whether the user can remove attachment from quotation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quotation $quotation
     * @return bool
     */
    public function removeAttachment(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'remove quotation attachments');
    }
}