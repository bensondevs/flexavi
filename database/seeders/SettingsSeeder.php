<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Setting, SettingValue, Company };
use App\Enums\Setting\SettingType as Type;
use App\Enums\Setting\SettingValueDataType as ValueDataType;
use App\Enums\Invoice\InvoicePaymentMethod;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawSettings = [
            /**
             * System setting
             */
            [
                'type' => Type::System,
                'key' => 'notification_enability',
                'value_data_type' => ValueDataType::Bool,
                'value' => 'true',
            ],

            /**
             * Company settings
             */
            [
                'type' => Type::Company,
                'key' => 'vat_percentage',
                'value_data_type' => ValueDataType::Float,
                'value' => '20',
            ],
            
            /**
             * Workday settings
             */
            [
                'type' => Type::Workday,
                'key' => 'standard_worklist_quantity',
                'value_data_type' => ValueDataType::Int,
                'value' => '3',
            ],

            /**
             * Appointment settings
             */
            [
                'type' => Type::Worklist,
                'key' => 'max_daily_appointment_quantity',
                'value_data_type' => ValueDataType::Int,
                'value' => '100',
            ],
            
            /**
             * Invoice setting
             */
            [
                'type' => Type::Invoice,
                'key' => 'overdue_deadline_days',
                'value_data_type' => ValueDataType::Int,
                'value' => '14',
            ],
            [
                'type' => Type::Invoice,
                'key' => 'invoice_number_prefix',
                'value_data_type' => ValueDataType::String,
                'value' => '',
            ],
            [
                'type' => Type::Invoice,
                'key' => 'invoice_number_suffix',
                'value_data_type' => ValueDataType::String,
                'value' => '',
            ],
            [
                'type' => Type::Invoice,
                'key' => 'standard_payment_method',
                'value_data_type' => ValueDataType::Int,
                'value' => InvoicePaymentMethod::Cash,
            ],
            [
                'type' => Type::Invoice,
                'key' => 'standard_payment_term_quantity',
                'value_data_type' => ValueDataType::Int,
                'value' => '3',
            ]
        ];

        foreach ($rawSettings as $rawSetting) {
            $rawValue = $rawSetting['value'];
            unset($rawSetting['value']);

            $setting = Setting::create($rawSetting);
            $setting->setDefaultValue($rawValue);
        }
    }
}
