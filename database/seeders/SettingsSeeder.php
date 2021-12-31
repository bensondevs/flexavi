<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Setting, Company };
use App\Enums\Setting\SettingType as Type;

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
            ],

            /**
             * Company settings
             */
            [
                'type' => Type::Company,
                'key' => 'vat_percentage',
            ],
            [
                'type' => Type::Company,
                'key' => 'office_hours',
            ],

            /**
             * Appointment settings
             */
            
            /**
             * Workday settings
             */
            [
                'type' => Type::Workday,
                'key' => 'standard_worklist_quantity',
            ],
            [
                'type' => Type::Workday,
                'key' => 'max_workday_quantity',
            ]

            /**
             * Worklist settings 
             */
            [
                'type' => Type::Worklist,
                'key' => 'max_appointment_quantity',
            ],
            
            /**
             * Invoice setting
             */
            [
                'type' => Type::Invoice,
                'key' => 'overdue_deadline',
            ],
            [
                'type' => Type::Invoice,
                'key' => 'invoice_number_prefix',
            ],
            [
                'type' => Type::Invoice,
                'key' => 'invoice_number_suffix',
            ],
            [
                'type' => Type::Invoice,
                'key' => 'standard_payment_method',
            ],
            [
                'type' => Type::Invoice,
                'key' => 'standard_payment_term_quantity',
            ]
        ];

        Setting::insert(array_map(function ($rawSetting) {
            $rawSetting['id'] = generateUuid();
            $rawSetting['created_at'] = now();
            $rawSetting['updated_at'] = now();
            return $rawSetting;
        }, $rawSettings));
    }
}
