<?php

namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\User\User;
use Str;

class InvoiceLogService
{
    /**
     * Write a log to the database
     *
     * @param Invoice $invoice
     * @param string $logName
     * @param array $props
     * @return void
     */
    public function write(Invoice $invoice, string $logName, array $props = []): void
    {
        $message = $this->getMessage($logName, $invoice, $props, auth()->user() ?? null);
        $data = [
            'message' => $message,
        ];

        $invoice->logs()->create($data);
    }

    /**
     * Get message from resource
     *
     * @param string $logName
     * @param Invoice $invoice
     * @param array $props
     * @param User|null $user
     * @return array
     */
    public function getMessage(string $logName, Invoice $invoice, array $props = [], User $user = null): array
    {
        $nlContent = $this->formatMessage($logName, 'nl', $invoice, $props, $user);
        $enContent = $this->formatMessage($logName, 'en', $invoice, $props, $user);

        return [
            'nl' => $nlContent,
            'en' => $enContent,
        ];
    }

    /**
     * Format message
     *
     * @param string $logName
     * @param string $locale
     * @param Invoice $invoice
     * @param array $props
     * @param User|null $user
     * @return ?string
     */
    public function formatMessage(string $logName, string $locale, Invoice $invoice, array $props, User $user = null): ?string
    {
        $content = trans('invoice_logs.' . $logName, [], $locale);
        $variables = Str::matchAll("/:([A-Za-z0-9_.]+)/", $content);
        if (count($variables) == -0) {
            return $content;
        }
        $customer = $invoice->customer;
        $replaces = [];
        foreach ($variables->toArray() as $variable) {
            $data = ${Str::before($variable, ".")};
            $key = Str::after($variable, ".");
            if (is_null($data)) return null;

            $replaces[$variable] = arrayobject_accessor($data, $key);
        }
        return trans('invoice_logs.' . $logName, $replaces, $locale);
    }
}
