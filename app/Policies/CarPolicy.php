<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\Car;
use App\Models\Company;

use App\Enums\Car\CarStatus;

class CarPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any cars');
    }

    public function view(User $user, Car $car)
    {
        return $user->hasCompanyPermission($car->company_id, 'view cars');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create cars');
    }

    public function setImage(User $user, Car $car)
    {
        return $user->hasCompanyPermission($car->company_id, 'set image cars');
    }

    public function edit(User $user, Car $car)
    {
        return $user->hasCompanyPermission($car->company_id, 'edit cars');
    }

    public function delete(User $user, Car $car)
    {
        if ($car->status !== CarStatus::Free) {
            return abort(403, 'Cannot delete not free car.');
        }

        return $user->hasCompanyPermission($car->company_id, 'delete cars');
    }

    public function restore(User $user, Car $car)
    {
        return $user->hasCompanyPermission($car->company_id, 'restore cars');
    }

    public function forceDelete(User $user, Car $car)
    {
        if (! $this->delete($user, $car)) {
            return true;
        }

        return $user->hasCompanyPermission($car->company_id, 'force delete cars');
    }
}
