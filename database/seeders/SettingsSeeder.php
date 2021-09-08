<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Setting;
use App\Models\Company;

use App\Enums\Setting\SettingType;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawSettings = [];

        // Default settings
        $rawSettings[] = [
            'id' => generateUuid(),
            'type' => SettingType::Default,
            'key' => 'vat_percentage',
            'value' => '21',
        ];

        $rawSettings[] = [
            'id' => generateUuid(),
            'type' => SettingType::Default,
            'key' => 'notification_enabled',
            'value' => 'true',
        ];

        Setting::insert($rawSettings);
        $rawSettings = [];

        foreach (Company::all() as $company) {
            if (rand(0, 1)) {
                $rawSettings[] = [
                    'id' => generateUuid(),
                    'type' => SettingType::Company,
                    'settingable_type' => Company::class,
                    'settingable_id' => $company->id,
                    'key' => 'vat_percentage',
                    'value' => rand(0, 100),
                ];

                $rawSettings[] = [
                    'id' => generateUuid(),
                    'type' => SettingType::Company,
                    'settingable_type' => Company::class,
                    'settingable_id' => $company->id,
                    'key' => 'notification_enabled',
                    'value' => 'true',
                ];
            }
        }
        Setting::insert($rawSettings);
    }
}
