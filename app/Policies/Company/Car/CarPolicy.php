<?php

namespace App\Policies\Company\Car;

use App\Enums\Car\CarStatus;
use App\Models\Car\Car;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine the user can view any car
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any cars');
    }

    /**
     * Determine the user can view car
     *
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function view(User $user, Car $car): bool
    {
        return $user->hasCompanyDirectPermission($car->company_id, 'view cars');
    }

    /**
     * Determine the user can create car
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('create cars');
    }

    /**
     * Determine the user can set image car
     *
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function setImage(User $user, Car $car): bool
    {
        return $user->hasCompanyDirectPermission($car->company_id, 'set image cars');
    }

    /**
     * Determine the user can edit car
     *
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function edit(User $user, Car $car): bool
    {
        return $user->hasCompanyDirectPermission($car->company_id, 'edit cars');
    }

    /**
     * Determine the user can restore car
     *
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function restore(User $user, Car $car): bool
    {
        return $user->hasCompanyDirectPermission($car->company_id, 'restore cars');
    }

    /**
     * Determine the user can force delete car
     *
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function forceDelete(User $user, Car $car): bool
    {
        if (!$this->delete($user, $car)) {
            return true;
        }

        return $user->hasCompanyDirectPermission($car->company_id, 'force delete cars');
    }

    /**
     * Determine the user can delete car
     *
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function delete(User $user, Car $car): bool
    {
        if ($car->status !== CarStatus::Free) {
            return abort(403, 'Cannot delete not free car.');
        }

        return $user->hasCompanyDirectPermission($car->company_id, 'delete cars');
    }
}
