<?php

namespace Database\Seeders;

use App\Models\Quotation\Quotation;
use App\Models\Work\Work;
use Illuminate\Database\Seeder;

class QuotationWorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotations = Quotation::all();
        $units = ['m2', 'cm2', 'm', 'l', 'dm3'];

        $rawWorks = [];
        foreach ($quotations as $key => $quotation) {
            for ($index = 0; $index < rand(1, 7); $index++) {
                $unitPrice = rand(1, 20) * 10;
                $quantity = rand(1, 10) * 10;
                $subTotal = $unitPrice * $quantity;

                $taxPercentage = 0;
                $taxAmount = 0;
                if ($includeTax = (bool) rand(0, 1)) {
                    $taxPercentage = rand(0, 1) ? 9 : 21;
                    $taxAmount = $subTotal * ($taxPercentage / 100);
                }
                $totalPrice = $subTotal + $taxAmount;

                array_push($rawWorks, [
                    'id' => generateUuid(),
                    'company_id' => $quotation->company_id,
                    'quotation_id' => $quotation->id,
                    'appointment_id' => $quotation->appointment_id,
                    'quantity' => $quantity,
                    'quantity_unit' => $units[rand(0, (count($units) - 1))],
                    'description' => 'This is seeder quotation work',
                    'unit_price' => $unitPrice,
                    'include_tax' => $includeTax,
                    'tax_percentage' => $taxPercentage,
                    'total_price' => $totalPrice,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }

        foreach (array_chunk($rawWorks, 25) as $chunk) {
            Work::insert($chunk);
        }
    }
}
