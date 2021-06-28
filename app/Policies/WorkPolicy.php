<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, $quotationOrContract)
    {
        return $user->hasCompanyPermission($quotationOrContract->company_id, 'view works');
    }

    public function view(User $user, Work $work)
    {
        if ($quotation = $work->quotation) {
            if (! $user->hasCompanyPermission($quotation->company_id, 'view works')) {
                return false;
            }
        }

        if ($contract = $work->contract) {
            if (! $user->hasCompanyPermission($contract->company_id, 'view works')) {
                return false;
            }
        }

        return true;
    }

    public function create(User $user, $quotationOrContract)
    {
        return $user->hasCompanyPermission($quotationOrContract->company_id, 'create works');
    }

    public function update(User $user, Work $work)
    {
        if ($quotation = $work->quotation) {
            if (! $user->hasCompanyPermission($quotation->company_id, 'edit works')) {
                return false;
            }
        }

        if ($contract = $work->contract) {
            if (! $user->hasCompanyPermission($contract->company_id, 'edit works')) {
                return false;
            }
        }

        return true;
    }

    public function delete(User $user, Work $work)
    {
        if ($quotation = $work->quotation) {
            if (! $user->hasCompanyPermission($quotation->company_id, 'delete works')) {
                return false;
            }
        }

        if ($contract = $work->contract) {
            if (! $user->hasCompanyPermission($contract->company_id, 'delete works')) {
                return false;
            }
        }

        return true;
    }

    public function restore(User $user, Work $work)
    {
        if ($quotation = $work->quotation) {
            if (! $user->hasCompanyPermission($quotation->company_id, 'restore works')) {
                return false;
            }
        }

        if ($contract = $work->contract) {
            if (! $user->hasCompanyPermission($contract->company_id, 'restore works')) {
                return false;
            }
        }

        return true;
    }

    public function forceDelete(User $user, Work $work)
    {
        if ($quotation = $work->quotation) {
            if (! $user->hasCompanyPermission($quotation->company_id, 'force delete works')) {
                return false;
            }
        }

        if ($contract = $work->contract) {
            if (! $user->hasCompanyPermission($contract->company_id, 'force delete works')) {
                return false;
            }
        }

        return true;
    }
}
