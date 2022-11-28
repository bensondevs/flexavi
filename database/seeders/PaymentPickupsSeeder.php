<?php

namespace Database\Seeders;

use App\Enums\Appointment\AppointmentType;
use App\Enums\PaymentTerm\PaymentTermStatus;
use App\Models\{Appointment\Appointment, Invoice\Invoice, PaymentPickup\PaymentPickup, PaymentPickup\PaymentPickupItem};
use Illuminate\Database\Seeder;

class PaymentPickupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawPaymentPickups = [];
        $rawPaymentPickupItems = [];
        $appointments = Appointment::where('type', AppointmentType::PaymentPickUp)->get();

        foreach ($appointments  as $index => $appointment) {
            $amountInvoice = rand(1, 3);
            $invoices = Invoice::with('paymentTerms')->inRandomOrder()->take($amountInvoice)->get();
            $paymentPickupId = generateUuid();
            array_push($rawPaymentPickups, [
                'id' => $paymentPickupId,
                'company_id' => $appointment->company_id,
                'appointment_id' => $appointment->id,

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $shouldBePickup = 0;
            foreach ($invoices as $invoice) {
                $pickupAmount = $invoice->paymentTerms()->where('status', PaymentTermStatus::Unpaid)->sum('amount');
                $shouldBePickup += $pickupAmount;
                array_push($rawPaymentPickupItems, [
                    'id' => generateUuid(),
                    'payment_pickup_id' => $paymentPickupId,
                    'invoice_id' => $invoice->id,
                    'total_bill' => $invoice->total_unpaid,
                    'pickup_amount' => $pickupAmount,
                    'payment_term_ids' => json_encode($invoice->paymentTerms()->pluck('id')->toArray()),
                    'note' => 'This is seeder',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $rawPaymentPickups[$index]['should_pickup_amount'] = $shouldBePickup;
        }

        foreach (array_chunk($rawPaymentPickups, 50) as $rawPaymentPickupsChunk) {
            PaymentPickup::insert($rawPaymentPickupsChunk);
        }

        foreach (array_chunk($rawPaymentPickupItems, 50) as $rawPaymentPickupItemsChunk) {
            PaymentPickupItem::insert($rawPaymentPickupItemsChunk);
        }
    }
}
