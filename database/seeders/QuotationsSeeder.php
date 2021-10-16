<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Appointment;

use App\Enums\Appointment\AppointmentType;

class QuotationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = AppointmentType::getValue('Quotation');
        $appointments = Appointment::where('type', $type)->get();

        $rawQuotations = [];
        foreach ($appointments as $index => $appointment) {
            // Prepare customer
            $customer = $appointment->customer;

            // Prepare damage causes
            $damageCauses = [];
            for ($causeIndex = 1; $causeIndex < 6; $causeIndex++) {
                if (rand(0, 1)) {
                    array_push($damageCauses, $causeIndex);
                }

                if ($causeIndex == 5 && $damageCauses == []) {
                    array_push($damageCauses, $causeIndex);
                }
            }

            // Prepare amount of prices
            $amount = rand(500, 3000);
            $vatPercentage = rand(0, 1) ? 9 : 21;
            $discountAmount = rand(0, 300);
            $totalAmount = $amount + (($vatPercentage / 100) * $amount) - $discountAmount;

            array_push($rawQuotations, [
                'id' => generateUuid(),
                'company_id' => $appointment->company_id,
                'customer_id' => $customer->id,
                'appointment_id' => $appointment->id,
                'type' => rand(1, 4),
                'quotation_date' => carbon()->now()->addDays(rand(-5, 5)),
                'quotation_number' => strtoupper(randomString(8)),
                'contact_person' => $customer->fullname,
                'address' => 'Random Address',
                'zip_code' => '111000',
                'phone_number' => '02861282634',
                'damage_causes' => json_encode($damageCauses),
                'quotation_description' => 'Hello this is seeder quotation damage descripton',
                'amount' => $amount,
                'vat_percentage' => $vatPercentage,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,

                'expiry_date' => carbon()->now()->addDays(rand(3, 10)),

                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ]);
        }

        foreach (array_chunk($rawQuotations, 1000) as $rawQuotationsChunk) {
            Quotation::insert($rawQuotationsChunk);
        }

    }
}
