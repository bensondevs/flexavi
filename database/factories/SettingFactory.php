<?php

namespace Database\Factories;

use App\Enums\Setting\Dashboard\DashboardDefaultCostTrend;
use App\Enums\Setting\Dashboard\DashboardSettingKey;
use App\Enums\Setting\SettingModule;
use App\Models\Company\Company;
use App\Models\Setting\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Setting $setting) {
            if (!$setting->company_id) {
                $company = Company::factory()->create();
                $setting->company()->associate($company);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'module' => SettingModule::Dashboard,
            'key' => DashboardSettingKey::DefaultCostTrend,
            'value' => DashboardDefaultCostTrend::Weekly,
        ];
    }
}
