<?php

namespace App\Observers;

use App\Models\{ Setting, SettingValue };
use App\Enums\SettingValue\SettingValueType as ValueType;

class SettingObserver
{
    /**
     * Handle the Setting "creating" event.
     *
     * @param  \App\Models\Setting  $setting
     * @return void
     */
    public function creating(Setting $setting)
    {
        $setting->id = generateUuid();
    }

    /**
     * Handle the Setting "created" event.
     *
     * @param  \App\Models\Setting  $setting
     * @return void
     */
    public function created(Setting $setting)
    {
        SettingValue::create([
            'setting_id' => $setting->id,
            'value_type' => ValueType::Default,
            'value' => null,
        ]);
    }

    /**
     * Handle the Setting "updated" event.
     *
     * @param  \App\Models\Setting  $setting
     * @return void
     */
    public function updated(Setting $setting)
    {
        //
    }

    /**
     * Handle the Setting "deleted" event.
     *
     * @param  \App\Models\Setting  $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
        //
    }

    /**
     * Handle the Setting "restored" event.
     *
     * @param  \App\Models\Setting  $setting
     * @return void
     */
    public function restored(Setting $setting)
    {
        //
    }

    /**
     * Handle the Setting "force deleted" event.
     *
     * @param  \App\Models\Setting  $setting
     * @return void
     */
    public function forceDeleted(Setting $setting)
    {
        //
    }
}
