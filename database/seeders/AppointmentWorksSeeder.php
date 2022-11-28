<?php

namespace Database\Seeders;

use App\Models\Appointment\Appointment;
use App\Models\Work\Work;
use Illuminate\Database\Seeder;

class AppointmentWorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::whereDoesntHave('quotation')->get();

        $units = ['m2', 'cm2', 'm', 'l', 'dm3'];

        $rawWorks = [];
        foreach ($appointments as $appointment) {
            for ($index = 0; $index < rand(1, 10); $index++) {
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
                    'id' => generateUuid(),
                    'company_id' => $appointment->company_id,
                    'appointment_id' => $appointment->id,
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
            }
        }

        foreach (array_chunk($rawWorks, 25) as $chunk) {
            Work::insert($chunk);
        }
    }
}
