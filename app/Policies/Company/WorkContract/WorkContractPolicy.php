<?php

namespace App\Policies\Company\WorkContract;

use App\Models\Customer\Customer;
use App\Models\Employee\Employee;
use App\Models\User\User;
use App\Models\WorkContract\WorkContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkContractPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any work contracts.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any work contracts');
    }

    /**
     * Determine whether the user can view any customer work contracts.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function viewAnyCustomer(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'view work contracts');
    }

    /**
     * Determine whether the user can view any employee work contracts.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function viewAnyEmployee(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'view work contracts');
    }

    /**
     * Determine whether the user can view quotation.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function view(User $user, WorkContract $workContract): bool
    {
        return $user->hasCompanyDirectPermission($workContract->company_id, 'view work contracts');
    }

    /**
     * Determine whether the user can create quotation.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('create work contracts');
    }

    /**
     * Determine whether the user can view work contracts.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function update(User $user, WorkContract $workContract): bool
    {
        if (!$workContract->canBeEdited()) {
            return abort(403, 'This work contract cannot be edited.');
        }
        return $user->hasCompanyDirectPermission($workContract->company_id, 'edit work contracts');
    }

    /**
     * Determine whether the user can delete work contract.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function delete(User $user, WorkContract $workContract): bool
    {
        if (!$workContract->canBeDeleted()) {
            return abort(403, 'This work contract cannot be deleted');
        }
        return $user->hasCompanyDirectPermission($workContract->company_id, 'delete work contracts');
    }

    /**
     * Determine whether the user can restore work contract.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function restore(User $user, WorkContract $workContract): bool
    {
        return $user->hasCompanyDirectPermission($workContract->company_id, 'restore work contracts');
    }

    /**
     * Determine whether the user can nullify work contract.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function nullify(User $user, WorkContract $workContract): bool
    {
        if (!$workContract->canBeNullified()) {
            return abort(403, 'This work contract cannot be nullified');
        }
        return $user->hasCompanyDirectPermission($workContract->company_id, 'nullify work contracts');
    }

    /**
     * Determine whether the user can force delete work contract.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function forceDelete(User $user, WorkContract $workContract): bool
    {
        if (!$workContract->canBeDeleted()) {
            return abort(403, 'This work contract cannot be deleted');
        }
        return $user->hasCompanyDirectPermission($workContract->company_id, 'force delete work contracts');
    }

    /**
     * Determine whether the user can set as draft work contract.
     *
     * @param User $user
     * @param WorkContract|null $workContract
     * @return bool
     */
    public function draft(User $user, WorkContract $workContract = null): bool
    {
        if (!$workContract) {
            return $user->hasDirectPermissionTwo('create work contracts');
        }

        if (!$workContract->canBeEdited()) {
            return abort(403, 'This work contract is already a draft');
        }

        return $user->hasCompanyDirectPermission($workContract->company_id, 'edit work contracts');
    }

    /**
     * Determine whether the user can set work contract as default.
     *
     * @param User $user
     * @return bool
     */
    public function setAsDefaultSetting(User $user): bool
    {
        return $user->hasDirectPermission('set work contract as default format');
    }

    /**
     * Determine whether the user can set work contract as send
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function send(User $user, WorkContract $workContract): bool
    {
        return $user->hasCompanyDirectPermission($workContract->company_id, 'send work contracts');
    }

    /**
     * Determine whether the user can set work contract as print
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function print(User $user, WorkContract $workContract): bool
    {
        return $user->hasCompanyDirectPermission($workContract->company_id, 'print work contracts');
    }

    /**
     * Upload signed document.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function uploadSignedDoc(User $user, WorkContract $workContract): bool
    {
        if ($workContract->canBeSigned() or $workContract->isSigned()) {
            return $user->hasCompanyDirectPermission($workContract->company_id, 'upload signed document work contracts');
        }
        return abort(403, 'This work contract cannot be signed');
    }

    /**
     * Remove signed document.
     *
     * @param User $user
     * @param WorkContract $workContract
     * @return bool
     */
    public function removeSignedDoc(User $user, WorkContract $workContract): bool
    {
        if (!$workContract->isSigned()) {
            return abort(403, 'This work contract cannot be signed');
        }
        return $user->hasCompanyDirectPermission($workContract->company_id, 'remove signed document work contracts');
    }

    public function applyCompanyFormat(User $user, WorkContract $workContract): bool
    {
        if (!$workContract->canBeEdited()) {
            return abort(403, 'This work contract cannot be edited');
        }
        return $user->hasCompanyDirectPermission($workContract->company_id, 'apply company format work contracts');
    }
}
