<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Invoice;
use App\Models\InvoiceItem;

use App\Jobs\Test\SyncInvoiceItemsTotal;

class InvoiceItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoices = Invoice::all();
        $units = ['m2', 'cm2', 'm', 'l', 'dm3'];

        $rawItems = [];
        foreach ($invoices as $invoice) {
            for ($index = 0; $index < rand(1, 10); $index++) {
                $unitPrice = rand(1, 20) * 10;
                $quantity = rand(1, 10) * 10;

                $rawItems[] = [
                    'id' => generateUuid(),

                    'company_id' => $invoice->company_id,
                    'invoice_id' => $invoice->id,

                    'item_name' => 'Item From Invoice',
                    'description' => 'Seeder Generated Record',
                    'quantity' => $quantity,
                    'quantity_unit' => $units[rand(0, (count($units) - 1))],
                    'amount' => $unitPrice,

                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }
        }

        foreach (array_chunk($rawItems, 5000) as $chunk) {
            InvoiceItem::insert($chunk);
        }

        dispatch(new SyncInvoiceItemsTotal());
    }
}
