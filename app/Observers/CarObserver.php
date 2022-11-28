<?php

namespace App\Observers;

use App\Models\Car\Car;
use App\Models\StorageFile\StorageFile;
use App\Services\Log\LogService;

class CarObserver
{
    /**
     * Handle the Car "creating" event.
     *
     * @param Car $car
     * @return void
     */
    public function creating(Car $car)
    {
        $car->id = generateUuid();
    }

    /**
     * Handle the Car "created" event.
     *
     * @param Car $car
     * @return void
     */
    public function created(Car $car)
    {
        if ($user = auth()->user())
            LogService::make("car.store")->by($user)->on($car)->write();
    }

    /**
     * Handle the Car "updating" event.
     *
     * @param Car $car
     * @return void
     */
    public function updating(Car $car)
    {
        session()->put("props.old.car", $car->getOriginal());
    }

    /**
     * Handle the Car "updated" event.
     *
     * @param Car $car
     * @return void
     */
    public function updated(Car $car)
    {
        if (!$car->insured) {
            $car->insurance_tax = 0;
            $car->saveQuietly();
        }

        if ($user = auth()->user()) {
            if ($car->isDirty("brand"))
                LogService::make("car.updates.brand")
                    ->with(
                        "old.subject.brand",
                        session("props.old.car")["brand"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("model"))
                LogService::make("car.updates.model")
                    ->with(
                        "old.subject.model",
                        session("props.old.car")["model"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("car_name"))
                LogService::make("car.updates.car_name")
                    ->with(
                        "old.subject.car_name",
                        session("props.old.car")["car_name"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("car_license"))
                LogService::make("car.updates.car_license")
                    ->with(
                        "old.subject.car_license",
                        session("props.old.car")["car_license"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("insured"))
                LogService::make("car.updates.insured")
                    ->with(
                        "old.subject.insured",
                        session("props.old.car")["insured"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("status"))
                LogService::make("car.updates.status")
                    ->with(
                        "old.subject.status",
                        session("props.old.car")["status"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("max_passenger"))
                LogService::make("car.updates.max_passenger")
                    ->with(
                        "old.subject.max_passenger",
                        session("props.old.car")["max_passenger"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("insurance_tax"))
                LogService::make("car.updates.insurance_tax")
                    ->with(
                        "old.subject.insurance_tax",
                        session("props.old.car")["insurance_tax"]
                    )
                    ->by($user)->on($car)->write();
            if ($car->isDirty("apk"))
                LogService::make("car.updates.apk")
                    ->with(
                        "old.subject.apk",
                        session("props.old.car")["apk"]
                    )
                    ->by($user)->on($car)->write();
        }

        session()->forget("props.old.customer");
    }

    /**
     * Handle the Car "deleted" event.
     *
     * @param Car $car
     * @return void
     */
    public function deleted(Car $car)
    {
        if ($file = StorageFile::findByPath($car->car_image_path)) {
            $date = now()->addDays(3);
            $file->setDestroyCountDown($date);
        }

        if ($user = auth()->user())
            LogService::make("car.delete")->by($user)->on($car)->write();
    }

    /**
     * Handle the Car "restored" event.
     *
     * @param Car $car
     * @return void
     */
    public function restored(Car $car)
    {
        if ($file = StorageFile::findByPath($car->car_image_path)) {
            $file->stopDestroyCountDown();
        }

        if ($user = auth()->user())
            LogService::make("car.restore")->by($user)->on($car)->write();
    }

    /**
     * Handle the Car "force deleted" event.
     *
     * @param Car $car
     * @return void
     */
    public function forceDeleted(Car $car)
    {
        if ($user = auth()->user())
            LogService::make("car.force_delete")->by($user)->on($car)->write();
    }
}
