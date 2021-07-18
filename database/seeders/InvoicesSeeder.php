<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Appointment;

use App\Jobs\Invoice\GenerateInvoiceNumber;

use App\Enums\Invoice\InvoiceStatus;

class InvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawInvoices = [];

        $quotations = Quotation::all();
        foreach ($quotations as $index => $quotation) {
            $rawInvoices[] = [
                'id' => generateUuid(),
                'company_id' => $quotation->company_id,
                'customer_id' => $quotation->customer_id,
                'referenceable_id' => $quotation->id,
                'referenceable_type' => get_class($quotation),
                'total' => $quotation->total_amount,
                'status' => rand(1, 13),
                'payment_method' => rand(1, 2),
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ];
        }

        $appointments = Appointment::with('works')
            ->whereDoesntHave('quotation')
            ->get();
        foreach ($appointments as $appointment) {
            $works = $appointment->works;
            $totalPrice = $works->sum('total_price');

            $rawInvoices[] = [
                'id' => generateUuid(),
                'company_id' => $appointment->company_id,
                'customer_id' => $appointment->customer_id,
                'referenceable_id' => $appointment->id,
                'referenceable_type' => get_class($appointment),
                'total' => $totalPrice,
                'status' => rand(1, 13),
                'payment_method' => rand(1, 2),
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ];
        }

        $chunks = array_chunk($rawInvoices, 5000);
        foreach ($chunks as $key => $chunk) {
            Invoice::insert($chunk);
        }

        $sentInvoices = Invoice::where('status', '>=', InvoiceStatus::Sent)->get();
        foreach ($sentInvoices as $sentInvoice) {
            dispatch(new GenerateInvoiceNumber($sentInvoice));
        }
    }
}
