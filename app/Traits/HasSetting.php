<?php

namespace App\Traits;

use App\Models\{ Setting, SettingValue };

trait HasSetting 
{
    /**
     * Get all setting values
     */
    public function settingValues()
    {
        //
    }

    /**
     * Set value of certain setting
     * 
     * @param  \App\Models\Setting  $setting
     * @param  mixed  $value
     * @return bool
     */
    public function configureSetting(Setting $setting, $value)
    {
        $value = new SettingValue([
            'setting_id' => $setting->id,
            'value' => $value,
        ]);

        if (auth()->check() && (! auth()->user()->hasRole('admin'))) {
            $company = auth()->user()->company;
            $value->company_id = $company->id;
        }

        return $value->save();
    }
}