<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\Car;
use App\Models\User;
use App\Models\Worklist;
use App\Models\CarRegisterTime;

class CarRegisterTimePolicy
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
        return $user->hasPermissionTo('view any car register times');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRegisterTime  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CarRegisterTime $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'view car register times');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create car register times');
    }

    /**
     * Determine whether the user can register car models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function register(User $user, Car $car)
    {
        return $user->hasCompanyPermission($car->company_id, 'register car times');
    }

    /**
     * Determine whether the user can register car models to worklist.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @param  \App\Models\Worklist  $worklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function registerToWorklist(User $user, Car $car, Worklist $worklist)
    {
        if ($car->company_id !== $worklist->company_id) {
            return abort(403, 'Cannot use data from other company\'s data.');
        }

        return $user->hasCompanyPermission($car->company_id, 'register worklist car times');
    }

    /**
     * Determine whether the user can mark car as out and record as marked out.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRegisterTime  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function markOut(User $user, CarRegisterTime $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'mark out car register times');
    }

    /**
     * Determine whether the user can mark car as returned and record as marked returned.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function markReturn(User $user, CarRegisterTime $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'mark return car register times');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRegisterTime  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CarRegisterTime $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'edit car register times');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRegisterTime  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CarRegisterTime $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'delete car register times');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRegisterTime  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CarRegisterTime $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'restore car register times');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRegisterTime  $time
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CarRegisterTime $time)
    {
        return $user->hasCompanyPermission($time->company_id, 'force delete car register times');
    }
}
