<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Work;
use App\Models\Quotation;

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

        $rawWorks = [];
        foreach ($quotations as $key => $quotation) {
            for ($index = 0; $index < rand(1, 7); $index++) {
                $units = ['m2', 'cm2', 'm', 'l', 'dm3'];

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

                array_push($rawWorks, [
                    'id' => generateUuid(),
                    'quotation_id' => $quotation->id,
                    'quantity' => $quantity,
                    'quantity_unit' => $units[rand(0, (count($units) - 1))],
                    'description' => 'This is seeder work',
                    'unit_price' => $unitPrice,
                    'include_tax' => $includeTax,
                    'tax_percentage' => $taxPercentage,
                    'total_price' => $totalPrice,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }
        Work::insert($rawWorks);
    }
}
