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

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view quotations');
    }

    public function viewAnyCustomer(User $user, Customer $customer)
    {
        return $user->hasCompanyPermission($customer->company_id, 'view quotations');
    }

    public function view(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'view quotations');
    }

    public function create(User $user, Customer $customer)
    {
        return $user->hasCompanyPermission($customer->company_id, 'create quotations');
    }

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

    public function send(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'send quotations');
    }

    public function print(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'print quotations');
    }

    public function revise(User $user, Quotation $quotation)
    {
        if ($quotation->status >= ((string) QuotationStatus::Honored)) {
            $currentStatus = QuotationStatus::getDescription($quotation->status);
            return abort(403, 'Quotation has been ' . $currentStatus . ', can no longer be revised');
        }

        return $user->hasCompanyPermission($quotation->company_id, 'revise quotations');
    }

    public function honor(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'honor quotations');
    }

    public function cancel(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'cancel quotations');
    }

    public function delete(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'delete quotations');
    }

    public function restore(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'restore quotations');
    }

    public function forceDelete(User $user, Quotation $quotation)
    {
        if ($quotation->status >= ((string) QuotationStatus::Sent)) {
            return abort(403, 'Force delete is not possible in this stage.');
        }

        return $user->hasCompanyPermission($quotation->company_id, 'force delete quotations');
    }

    public function addAttachment(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'add quotation attachments');
    }

    public function removeAttachment(User $user, Quotation $quotation)
    {
        return $user->hasCompanyPermission($quotation->company_id, 'remove quotation attachments');
    }
}