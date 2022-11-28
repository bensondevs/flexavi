<?php

namespace App\Policies\Company\Quotation;

use App\Models\Customer\Customer;
use App\Models\Employee\Employee;
use App\Models\Quotation\Quotation;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any quotations.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view quotations');
    }

    /**
     * Determine whether the user can view any customer quotations.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function viewAnyCustomer(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'view quotations');
    }

    /**
     * Determine whether the user can view any employee quotations.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function viewAnyEmployee(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'view quotations');
    }

    /**
     * Determine whether the user can view quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function view(User $user, Quotation $quotation): bool
    {
        return $user->hasCompanyDirectPermission($quotation->company_id, 'view quotations');
    }

    /**
     * Determine whether the user can create quotation.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('create quotations');
    }

    /**
     * Determine whether the user can view quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @param Customer $customer
     * @return bool
     */
    public function update(User $user, Quotation $quotation, Customer $customer): bool
    {
        if ($quotation->company_id != $customer->company_id) {
            return abort(403, 'Cannot update quotation using other company data.');
        }

        if (!$quotation->canBeEdited()) {
            return abort(403, 'Cannot update quotation that is not in draft or created status.');
        }

        if (!$user->hasCompanyDirectPermission($quotation->company_id, 'edit quotations')) {
            return abort(403, 'You don\'t have permission to update quotation');
        }

        return true;
    }


    /**
     * Determine whether the user can send quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function send(User $user, Quotation $quotation): bool
    {
        return $user->hasCompanyDirectPermission($quotation->company_id, 'send quotations');
    }

    /**
     * Determine whether the user can send quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function print(User $user, Quotation $quotation): bool
    {
        return $user->hasCompanyDirectPermission($quotation->company_id, 'print quotations');
    }

    /**
     * Determine whether the user can generate invoice from quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function generateInvoice(User $user, Quotation $quotation): bool
    {
        return $user->hasCompanyDirectPermission($quotation->company_id, 'generate invoice quotations');
    }

    /**
     * Determine whether the user can delete quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function nullify(User $user, Quotation $quotation): bool
    {
        if (!$quotation->canBeNullified()) {
            return abort(403, 'Nullify quotation is not possible in this stage');
        }
        return $user->hasCompanyDirectPermission($quotation->company_id, 'nullify quotations');
    }


    /**
     * Determine whether the user can delete quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function delete(User $user, Quotation $quotation): bool
    {
        if (!$quotation->canBeDeleted()) {
            return abort(403, 'Delete is not possible in this stage.');
        }
        return $user->hasCompanyDirectPermission($quotation->company_id, 'delete quotations');
    }

    /**
     * Determine whether the user can restore quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function restore(User $user, Quotation $quotation): bool
    {
        return $user->hasCompanyDirectPermission($quotation->company_id, 'restore quotations');
    }

    /**
     * Determine whether the user can draft quotation.
     *
     * @param User $user
     * @param Quotation|null $quotation
     * @return bool
     */
    public function draft(User $user, Quotation $quotation = null): bool
    {
        if (!$quotation) {
            return $user->hasDirectPermissionTwo('create quotations');
        }

        if (!$quotation->canBeEdited()) {
            return abort(403, 'This quotation cannot be edited.');
        }

        return $user->hasCompanyDirectPermission($quotation->company_id, 'edit quotations');
    }

    /**
     * Determine whether the user can upload signed doc.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function uploadSignedDoc(User $user, Quotation $quotation): bool
    {
        // Signed document should be at least being sent
        // Drafted document is not allowed to be signed
        if (!$quotation->isSent()) {
            return abort(403, 'Upload signed doc is not possible in this stage');
        }

        return $user->hasCompanyDirectPermission(
            $quotation->company_id,
            'upload signed document quotations',
        );
    }

    /**
     * Determine whether the user can remove signed doc.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function removeSignedDoc(User $user, Quotation $quotation): bool
    {
        if (!$quotation->hasSignedDocument()) {
            return abort(403, 'The quotation does not have signed document');
        }

        if ($quotation->isSigned() or $quotation->isNullified()) {
            return $user->hasCompanyDirectPermission($quotation->company_id, 'remove signed document quotations');

        }

        return abort(403, 'Remove signed doc is not possible in this stage');

    }

    /**
     * Determine whether the user can force delete quotation.
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function forceDelete(User $user, Quotation $quotation): bool
    {
        if (!$quotation->trashed()) {
            return abort(403, 'Force delete is not possible in this stage.');
        }

        return $user->hasCompanyDirectPermission($quotation->company_id, 'force delete quotations');
    }

    /**
     * Determine whether the user can view any quotation log
     *
     * @param User $user
     * @param Quotation $quotation
     * @return bool
     */
    public function viewAnyQuotationLog(User $user, Quotation $quotation): bool
    {
        return $user->hasCompanyDirectPermission($quotation->company_id, 'view any quotation logs');
    }

}
