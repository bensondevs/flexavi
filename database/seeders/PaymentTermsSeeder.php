<?php

namespace Database\Seeders;

use App\Jobs\Test\SyncInvoicePaymentTerms;
use App\Models\Invoice\Invoice;
use App\Models\PaymentPickup\PaymentTerm;
use Illuminate\Database\Seeder;

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

            $termQuantity = rand(1, 3);

            // Break down the price to each term
            for ($quantity = 0; $quantity < $termQuantity; $quantity++) {
                $termAmount = $total / $termQuantity;
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
            }
        }

        foreach (array_chunk($rawPaymentTerms, 25) as $chunk) {
            PaymentTerm::insert($chunk);
        }

        // Sync invoice payment terms
        $sync = new SyncInvoicePaymentTerms();
        dispatch($sync);
    }
}
