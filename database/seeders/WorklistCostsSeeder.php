<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Cost, Worklist, Workday, Costable };

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
                array_push($rawCosts, [
                    'id' => $id,
                    'company_id' => $worklist->company_id,
                    'cost_name' => 'Worklist Cost Seeder #' . ($index + 1),
                    'amount' => 2000,
                    'paid_amount' => 400,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                array_push($rawCostables, [
                    'cost_id' => $id,
                    'company_id' => $worklist->company_id,
                    'costable_id' => $worklist->id,
                    'costable_type' => Worklist::class,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($workday = $worklist->workday) {
                    array_push($rawCostables, [
                        'cost_id' => $id,
                        'company_id' => $workday->company_id,
                        'costable_id' => $workday->id,
                        'costable_type' => Workday::class,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        foreach (array_chunk($rawCosts, 5000) as $chunk) {
            Cost::insert($chunk);
        }

        foreach (array_chunk($rawCostables, 5000) as $chunk) {
            Costable::insert($chunk);
        }
    }
}