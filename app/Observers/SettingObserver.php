<?php

namespace App\Observers;

use App\Models\{Setting\Setting};

class SettingObserver
{
    /**
     * Handle the Setting "creating" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function creating(Setting $setting)
    {
        $setting->id = generateUuid();
    }

    /**
     * Handle the Setting "created" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function created(Setting $setting)
    {
    }

    /**
     * Handle the Setting "updated" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function updated(Setting $setting)
    {
        //
    }

    /**
     * Handle the Setting "deleted" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
        //
    }

    /**
     * Handle the Setting "restored" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function restored(Setting $setting)
    {
        //
    }

    /**
     * Handle the Setting "force deleted" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function forceDeleted(Setting $setting)
    {
        //
    }
}
