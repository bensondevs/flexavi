<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    Work,
    Revenue,
    Revenueable
};
use App\Enums\Work\WorkStatus;
use App\Jobs\Test\SyncWorkRevenue;

class RevenuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawRevenues = [];
        $rawRevenueables = [];
        foreach (Work::onlyStatus(WorkStatus::Finished)->get() as $work) {
            $id = generateUuid();

            $rawRevenues[] = [
                'id' => $id,
                'company_id' => $work->company_id,
                'revenue_name' => $work->description,
                'amount' => $work->total_price,
                'paid_amount' => rand(0, $work->total_price),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $rawRevenueables[] = [
                'id' => generateUuid(),
                'revenue_id' => $id,
                'revenueable_type' => Work::class,
                'revenueable_id' => $work->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($rawRevenues, 5000) as $chunk) {
            Revenue::insert($chunk);
        }

        foreach (array_chunk($rawRevenueables, 5000) as $chunk) {
            Revenueable::insert($chunk);
        }

        $job = new SyncWorkRevenue();
        dispatch($job);
    }
}