<?php

namespace Database\Seeders;

use App\Models\{Cost\Cost, Cost\Costable, Workday\Workday, Worklist\Worklist};
use Illuminate\Database\Seeder;

class WorklistCostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawCosts = [];
        $rawCostables = [];

        foreach (Worklist::with('workday')->get() as $worklist) {
            for ($index = 1; $index < rand(1, 3); $index++) {
                $id = generateUuid();

                $createdAt = now()->subDays(rand(0, 30));
                $updatedAt = rand(0, 1) ? $createdAt->addDays(rand(1, 10)) : null;

                $amount = rand(500, 5000);
                $paidAmount = rand(0, $amount);

                array_push($rawCosts, [
                    'id' => $id,
                    'company_id' => $worklist->company_id,
                    'cost_name' => 'Worklist Cost Seeder #' . ($index + 1),
                    'amount' => $amount,
                    'paid_amount' => $paidAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                array_push($rawCostables, [
                    'cost_id' => $id,
                    'company_id' => $worklist->company_id,
                    'costable_id' => $worklist->id,
                    'costable_type' => Worklist::class,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                if ($workday = $worklist->workday) {
                    array_push($rawCostables, [
                        'cost_id' => $id,
                        'company_id' => $workday->company_id,
                        'costable_id' => $workday->id,
                        'costable_type' => Workday::class,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ]);
                }
            }
        }

        foreach (array_chunk($rawCosts, 25) as $chunk) {
            Cost::insert($chunk);
        }

        foreach (array_chunk($rawCostables, 25) as $chunk) {
            Costable::insert($chunk);
        }
    }
}
