<?php

namespace App\Observers;

use App\Enums\Worklist\WorklistSortingRouteStatus;
use App\Models\Worklist\Worklist;
use App\Services\Log\LogService;
use App\Services\RouteXL\RouteXLService;

class WorklistObserver
{
    /**
     * RouteXL Service container
     *
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
     * Handle the Worklist "saving" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function saving(Worklist $worklist)
    {
        //
    }

    /**
     * Handle the Worklist "saved" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function saved(Worklist $worklist)
    {
        if (
            $worklist->getOriginal('sorting_route_status') ==
            WorklistSortingRouteStatus::Inactive &&
            $worklist->sorting_route_status ==
            WorklistSortingRouteStatus::Active
        ) {
            $this->routeXLService->planRoutes($worklist);
        }
    }

    /**
     * Handle the Worklist "creating" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function creating(Worklist $worklist)
    {
        $worklist->id = generateUuid();
    }

    /**
     * Handle the Worklist "created" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function created(Worklist $worklist)
    {
        if (
            $worklist->getOriginal('sorting_route_status') ==
            WorklistSortingRouteStatus::Inactive &&
            $worklist->sorting_route_status ==
            WorklistSortingRouteStatus::Active
        ) {
            $this->routeXLService->planRoutes($worklist);
        }

        if ($user = auth()->user())
            LogService::make("worklist.store")->by($user)->on($worklist)->write();
    }

    /**
     * Handle the Worklist "updating" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function updating(Worklist $worklist)
    {
        session()->put("props.old.worklist", $worklist->getOriginal());
    }

    /**
     * Handle the Worklist "updated" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function updated(Worklist $worklist)
    {
        if (
            $worklist->getOriginal('sorting_route_status') ==
            WorklistSortingRouteStatus::Inactive &&
            $worklist->sorting_route_status ==
            WorklistSortingRouteStatus::Active
        ) {
            $this->routeXLService->planRoutes($worklist);
        }

        if ($user = auth()->user()) {
            if ($worklist->isDirty('workday_id'))
                LogService::make("worklist.updates.workday_id")->by($user)->on($worklist)->write();

            if ($worklist->isDirty('status'))
                LogService::make("worklist.updates.status")->by($user)->on($worklist)->write();

            if ($worklist->isDirty('worklist_name'))
                LogService::make("worklist.updates.worklist_name")
                    ->with("old.subject.worklist_name", session("props.old.worklist")["worklist_name"])
                    ->by($user)->on($worklist)->write();

            if ($worklist->isDirty('user_id'))
                LogService::make("worklist.updates.user_id")->by($user)->on($worklist)->write();

            if ($worklist->isDirty('sorting_route_status'))
                LogService::make("worklist.updates.sorting_route_status")
                    ->by($user)->on($worklist)->write();

            if ($worklist->isDirty('always_sorting_route_status'))
                LogService::make("worklist.updates.always_sorting_route_status")
                    ->by($user)->on($worklist)->write();
        }

        session()->forget("props.old.worklist");
    }

    /**
     * Handle the Worklist "deleted" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function deleted(Worklist $worklist)
    {
        if ($user = auth()->user())
            LogService::make("worklist.delete")->by($user)->on($worklist)->write();
    }

    /**
     * Handle the Worklist "restored" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function restored(Worklist $worklist)
    {
        if ($user = auth()->user())
            LogService::make("worklist.restore")->by($user)->on($worklist)->write();
    }

    /**
     * Handle the Worklist "force deleted" event.
     *
     * @param Worklist $worklist
     * @return void
     */
    public function forceDeleted(Worklist $worklist)
    {
        if ($user = auth()->user())
            LogService::make("worklist.force_delete")->by($user)->on($worklist)->write();
    }
}
