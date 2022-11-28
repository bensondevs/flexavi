<?php

namespace App\Http\Resources\Invoice;

use App\Models\Invoice\InvoiceReminder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InvoiceReminder */
class InvoiceReminderResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $invoice = $this->invoice;

        return [
            'automatic_reminder' => (bool)$invoice->auto_reminder_activated,
            'payment_method' => $invoice->payment_method,
            'payment_method_description' => $invoice->payment_method_description,
            'payment_due_date' => $invoice->due_date->diffInDays($invoice->date),

            'first_reminder' => 0,
            'first_reminder_sent_type' => $this->first_reminder_sent_type,
            'first_reminder_send_type_description' => $this->first_reminder_sent_type_description,

            'second_reminder' => $this->second_reminder_at->diffInDays($this->first_reminder_at),
            'second_reminder_sent_type' => $this->second_reminder_sent_type,
            'second_reminder_send_type_description' => $this->second_reminder_sent_type_description,

            'third_reminder' => $this->third_reminder_at->diffInDays($this->second_reminder_at),
            'third_reminder_sent_type' => $this->third_reminder_sent_type,
            'third_reminder_send_type_description' => $this->third_reminder_sent_type_description,

            'debt_collector' => $this->sent_to_debt_collector_at->diffInDays($this->third_reminder_at),
            'debt_collector_reminder_sent_type' => $this->debt_collector_reminder_sent_type,
            'debt_collector_reminder_sent_type_description' => $this->debt_collector_reminder_sent_type_description,
        ];
    }
}
