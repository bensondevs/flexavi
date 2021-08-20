<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Work;
use App\Models\Workable;
use App\Models\Quotation;
use App\Models\Appointment;

class WorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = ['m2', 'cm2', 'm', 'l', 'dm3'];

        $rawWorks = [];
        $rawWorkables = [];
        
        foreach (Appointment::all() as $appointment) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $id = generateUuid();
                $unitPrice = rand(10, 200);
                $quantity = rand(1, 1000);
                $subTotal = $unitPrice * $quantity;
                
                $taxPercentage = 0;
                $taxAmount = 0;
                if ($includeTax = (bool) rand(0, 1)) {
                    $taxPercentage = rand(0, 1) ? 9 : 21;
                    $taxAmount = $subTotal * ($taxPercentage / 100);
                }
                $totalPrice = $subTotal + $taxAmount;

                $rawWorks[] = [
                    'id' => $id,
                    'company_id' => $appointment->company_id,
                    'quantity' => $quantity,
                    'quantity_unit' => $units[rand(0, (count($units) - 1))],
                    'description' => 'This is seeder appointment work',
                    'unit_price' => $unitPrice,
                    'include_tax' => $includeTax,
                    'tax_percentage' => $taxPercentage,
                    'total_price' => $totalPrice,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];

                $rawWorkables[] = [
                    'work_id' => $id,
                    'workable_type' => Appointment::class,
                    'workable_id' => $appointment->id,
                ];
            }
        }

        foreach (Quotation::all() as $quotation) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $id = generateUuid();
                $unitPrice = rand(10, 200);
                $quantity = rand(1, 1000);
                $subTotal = $unitPrice * $quantity;
                
                $taxPercentage = 0;
                $taxAmount = 0;
                if ($includeTax = (bool) rand(0, 1)) {
                    $taxPercentage = rand(0, 1) ? 9 : 21;
                    $taxAmount = $subTotal * ($taxPercentage / 100);
                }
                $totalPrice = $subTotal + $taxAmount;

                $rawWorks[] = [
                    'id' => $id,
                    'company_id' => $quotation->company_id,
                    'quantity' => $quantity,
                    'quantity_unit' => $units[rand(0, (count($units) - 1))],
                    'description' => 'This is seeder quotation work',
                    'unit_price' => $unitPrice,
                    'include_tax' => $includeTax,
                    'tax_percentage' => $taxPercentage,
                    'total_price' => $totalPrice,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];

                $rawWorkables[] = [
                    'work_id' => $id,
                    'workable_type' => Quotation::class,
                    'workable_id' => $quotation->id,
                ];
            }
        }

        foreach (array_chunk($rawWorks, 1000) as $rawWorksChunk) {
            Work::insert($rawWorksChunk);
        }

        foreach (array_chunk($rawWorkables, 1000) as $rawWorkablesChunk) {
            Workable::insert($rawWorkablesChunk);
        }
    }
}
