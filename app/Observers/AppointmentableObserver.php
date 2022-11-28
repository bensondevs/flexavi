<?php

namespace App\Observers;

use App\Enums\Worklist\WorklistSortingRouteStatus;
use App\Models\Appointment\Appointmentable;
use App\Models\Worklist\Worklist;
use App\Services\RouteXL\RouteXLService;

class AppointmentableObserver
{
    /**
     * Position stack service
     * @var RouteXLService
     */
    private $routeXLService;

    /**
     * Observer constructor method
     *
     * @param RouteXLService $routeXLService
     * @return void
     */
    public function __construct(RouteXLService $routeXLService)
    {
        $this->routeXLService = $routeXLService;
    }

    /**
     * Handle the Appointmentable "creating" event.
     *
     * @param Appointmentable $appointmentable
     * @return void
     */
    public function creating(Appointmentable $appointmentable)
    {
        if (!$appointmentable->id) {
            $appointmentable->id = generateUuid();
        }
    }

    /**
     * Handle the Appointmentable "created" event.
     *
     * @param Appointmentable $appointmentable
     * @return void
     */
    public function saving(Appointmentable $appointmentable)
    {
        if (!$appointmentable->id) {
            $appointmentable->id = generateUuid();
        }
    }

    /**
     * Handle the Appointmentable "created" event.
     *
     * @param Appointmentable $appointmentable
     * @return void
     */
    public function created(Appointmentable $appointmentable)
    {
        if (
            $appointmentable->appointmentable_type == get_class(new Worklist())
        ) {
            $worklist = $appointmentable->worklist;
            if (is_null($worklist)) {
                $worklist = Worklist::find(
                    $appointmentable->appointmentable_id
                );
            }
            if (
                $worklist->always_sorting_route_status ==
                WorklistSortingRouteStatus::Active
            ) {
                $this->routeXLService->planRoutes($worklist);
            }
        } else {
            $appointmentable->setOrderIndex();
            $appointmentable->save();
        }
    }

    /**
     * Handle the Appointmentable "updated" event.
     *
     * @param Appointmentable $appointmentable
     * @return void
     */
    public function updated(Appointmentable $appointmentable)
    {
        if (
            $appointmentable->appointmentable_type == get_class(new Worklist())
        ) {
            $worklist = $appointmentable->worklist;
            if (is_null($worklist)) {
                $worklist = Worklist::find(
                    $appointmentable->appointmentable_id
                );
            }
            if (
                $worklist->always_sorting_route_status ==
                WorklistSortingRouteStatus::Active
            ) {
                $this->routeXLService->planRoutes($worklist);
            }
        } else {
            $appointmentable->reorderIndex();
        }
    }

    /**
     * Handle the Appointmentable "deleted" event.
     *
     * @param Appointmentable $appointmentable
     * @return void
     */
    public function deleted(Appointmentable $appointmentable)
    {
        if (
            $appointmentable->appointmentable_type == get_class(new Worklist())
        ) {
            $worklist = $appointmentable->worklist;
            if (is_null($worklist)) {
                $worklist = Worklist::find(
                    $appointmentable->appointmentable_id
                );
            }
            if (
                $worklist->always_sorting_route_status ==
                WorklistSortingRouteStatus::Active
            ) {
                $this->routeXLService->planRoutes($worklist);
            }
        } else {
            $appointmentable->reorderIndex();
        }
    }

    /**
     * Handle the Appointmentable "restored" event.
     *
     * @param Appointmentable $appointmentable
     * @return void
     */
    public function restored(Appointmentable $appointmentable)
    {
        if (
            $appointmentable->appointmentable_type == get_class(new Worklist())
        ) {
            $worklist = $appointmentable->worklist;
            if (is_null($worklist)) {
                $worklist = Worklist::find(
                    $appointmentable->appointmentable_id
                );
            }
            if (
                $worklist->always_sorting_route_status ==
                WorklistSortingRouteStatus::Active
            ) {
                $this->routeXLService->planRoutes($worklist);
            }
        } else {
            $appointmentable->reorderIndex();
        }
    }

    /**
     * Handle the Appointmentable "force deleted" event.
     *
     * @param Appointmentable $appointmentable
     * @return void
     */
    public function forceDeleted(Appointmentable $appointmentable)
    {
        if (
            $appointmentable->appointmentable_type == get_class(new Worklist())
        ) {
            $worklist = $appointmentable->worklist;
            if (is_null($worklist)) {
                $worklist = Worklist::find(
                    $appointmentable->appointmentable_id
                );
            }
            if (
                $worklist->always_sorting_route_status ==
                WorklistSortingRouteStatus::Active
            ) {
                $this->routeXLService->planRoutes($worklist);
            }
        } else {
            $appointmentable->reorderIndex();
        }
    }
}
