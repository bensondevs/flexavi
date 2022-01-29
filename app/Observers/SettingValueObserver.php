<?php

namespace App\Observers;

use App\Models\SettingValue;

class SettingValueObserver
{
    /**
     * Handle the SettingValue "creating" event.
     *
     * @param  \App\Models\SettingValue  $settingValue
     * @return void
     */
    public function creating(SettingValue $settingValue)
    {
        $settingValue->id = generateUuid();
    }

    /**
     * Handle the SettingValue "created" event.
     *
     * @param  \App\Models\SettingValue  $settingValue
     * @return void
     */
    public function created(SettingValue $settingValue)
    {
        //
    }

    /**
     * Handle the SettingValue "updated" event.
     *
     * @param  \App\Models\SettingValue  $settingValue
     * @return void
     */
    public function updated(SettingValue $settingValue)
    {
        //
    }

    /**
     * Handle the SettingValue "deleted" event.
     *
     * @param  \App\Models\SettingValue  $settingValue
     * @return void
     */
    public function deleted(SettingValue $settingValue)
    {
        //
    }

    /**
     * Handle the SettingValue "restored" event.
     *
     * @param  \App\Models\SettingValue  $settingValue
     * @return void
     */
    public function restored(SettingValue $settingValue)
    {
        //
    }

    /**
     * Handle the SettingValue "force deleted" event.
     *
     * @param  \App\Models\SettingValue  $settingValue
     * @return void
     */
    public function forceDeleted(SettingValue $settingValue)
    {
        //
    }
}
