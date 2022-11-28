<?php

namespace App\Observers;

use App\Models\Address\Address;
use App\Services\PositionStack\PositionStackService;

class AddressObserver
{
    /**
     * Position stack service
     * @var PositionStackService
     */
    private $positionStackService;

    /**
     * Observer constructor method
     *
     * @param PositionStackService $positionStackService
     * @return void
     */
    public function __construct(PositionStackService $positionStackService)
    {
        $this->positionStackService = $positionStackService;
    }

    /**
     * Handle the Address "saving" event.
     *
     * @param Address $address
     * @return void
     */
    public function saving(Address $address)
    {
        //
    }

    /**
     * Handle the Address "saved" event.
     *
     * @param Address $address
     * @return void
     */
    public function saved(Address $address)
    {
        if (is_null($address->latitude) or is_null($address->longitude)) $this->positionStackService->forward($address);
    }

    /**
     * Handle the Address "creating" event.
     *
     * @param Address $address
     * @return void
     */
    public function creating(Address $address)
    {
        $address->id = generateUuid();
    }

    /**
     * Handle the Address "created" event.
     *
     * @param Address $address
     * @return void
     */
    public function created(Address $address)
    {
        if (is_null($address->latitude) or is_null($address->longitude)) $this->positionStackService->forward($address);
    }

    /**
     * Handle the Address "updated" event.
     *
     * @param Address $address
     * @return void
     */
    public function updated(Address $address)
    {
        if (is_null($address->latitude) or is_null($address->longitude)) $this->positionStackService->forward($address);
    }

    /**
     * Handle the Address "deleted" event.
     *
     * @param Address $address
     * @return void
     */
    public function deleted(Address $address)
    {
        //
    }

    /**
     * Handle the Address "restored" event.
     *
     * @param Address $address
     * @return void
     */
    public function restored(Address $address)
    {
        //
    }

    /**
     * Handle the Address "force deleted" event.
     *
     * @param Address $address
     * @return void
     */
    public function forceDeleted(Address $address)
    {
        //
    }
}
