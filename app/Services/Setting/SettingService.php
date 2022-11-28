<?php

namespace App\Services\Setting;

use App\Enums\Setting\SettingModule;
use App\Models\Company\Company;
use Exception;
use Illuminate\Support\Arr;

class SettingService
{
    /**
     * Populate setting by the given company and module
     *
     * @param Company $company
     * @param int $module
     * @return ?object
     * @throws \Exception
     */
    public static function find(Company $company, int $module): ?object
    {
        try {
            $settingModuleNames = SettingModule::asSelectArray();
            $settingModuleName = $settingModuleNames[$module];

            $model = ("\App\Models\Setting\\$settingModuleName".'Setting') ;

            return
                $model::whereCompanyId($company->id)->first() ?? $model::default()->first();
        } catch (\Exception $e) {
            throw new Exception('Module must be exists in SettingModule');
        }
    }

    /**
     * Update or create setting
     *
     * @param array $data
     * @param int $module
     * @return ?object
     */
    public static function updateOrCreate(array $data, int $module): ?object
    {
        try {
            $settingModuleNames = SettingModule::asSelectArray();
            $settingModuleName = $settingModuleNames[$module];

            $model = ("\App\Models\Setting\\$settingModuleName".'Setting') ;
            $setting = $model::whereCompanyId($data['company_id'])->first() ?? new $model();
            $setting->fill($data);

            foreach (
                Arr::except($setting->getAttributes(), ['id' , 'company_id']) as $key => $value
            ) {
                if (is_null($value)) {
                    $setting->{$key} = $setting->default->{$key} ;
                }
            }

            $setting->save();
            return $setting;
        } catch (\Exception $e) {
            throw new Exception('Module must be exists in SettingModule');
        }
    }
}
