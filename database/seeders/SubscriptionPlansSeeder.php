<?php

namespace Database\Seeders;

use App\Enums\Currency;
use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        SubscriptionPlanPeriod::whereNotNull('id')->forceDelete();
        SubscriptionPlan::whereNotNull('id')->forceDelete();
        $data = [
            [

                'name' => 'BASIC',
                'base_price' => 100,
                'description' => [
                    "20 Employee",
                    "Fleet Maintenance",
                    "100 Customer",
                    "Recycle Item Deleted"
                ],

            ],
        ];

        foreach ($data as $row) {
            SubscriptionPlan::create($row);
        }

        $rawPeriods = [];
        foreach (SubscriptionPlan::get() as $plan) {
            $rawPeriods = [
                [
                    'id' => generateUuid(),
                    'subscription_plan_id' => $plan->id,
                    'name' => 'Monthly',
                    'description' => 'Monthly',
                    'interval' => '1 month',
                    'amount' => 100,
                    'currency' => Currency::EUR,
                    'first_payment_description' => 'First Payment Monthly',
                    'first_payment_amount' => 0.01,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => generateUuid(),
                    'subscription_plan_id' => $plan->id,
                    'name' => 'Yearly',
                    'description' => 'Yearly',
                    'interval' => '1 year',
                    'amount' => 1000,
                    'currency' => Currency::EUR,
                    'first_payment_description' => 'First Payment Yearly',
                    'first_payment_amount' => 0.01,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ];
        }
        foreach (array_chunk($rawPeriods, 50) as $rawPeriodsChunk) {
            SubscriptionPlanPeriod::insert($rawPeriodsChunk);
        }
    }
}
