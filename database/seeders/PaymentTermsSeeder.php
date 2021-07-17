<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Invoice;
use App\Models\PaymentTerm;

class PaymentTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawPaymentTerms = [];
        foreach (Invoice::all() as $invoice) {
            // Get total invoice
            $total = $invoice->total;
            
            // Break down the price to each term
            do {
                // Set Term Amount
                $termAmount = rand(1, $total);
                if ($termAmount > $total)
                    $termAmount = $total;

                $rawPaymentTerms[] = [
                    'id' => generateUuid(),
                    'company_id' => $invoice->company_id,
                    'invoice_id' => $invoice->id,
                    'term_name' => 'Another Term Name',
                    'status' => rand(1, 2),
                    'amount' => $termAmount,
                    'due_date' => carbon()->now()->addDays(rand(0, 10)),
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];

                // Substract the rest of total
                $total -= $termAmount;
            } while ($total > 0);
        }

        foreach (array_chunk($rawPaymentTerms, 1000) as $chunk) {
            PaymentTerm::insert($chunk);
        }
    }
}
