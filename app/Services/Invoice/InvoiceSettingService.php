<?php

namespace App\Services\Invoice;

use App\Enums\Invoice\InvoiceReminderSentType;
use App\Enums\Setting\Invoice\InvoiceSettingKey;
use App\Enums\Setting\SettingModule;
use App\Models\Invoice\Invoice;
use App\Models\Setting\Setting;
use App\Repositories\Invoice\InvoiceSettingRepository;

class InvoiceSettingService
{
    /**
     * Invoice setting repository
     *
     * @var InvoiceSettingRepository
     */
    private InvoiceSettingRepository $invoiceSettingRepository;

    /**
     * Service constructor
     *
     * @param InvoiceSettingRepository $invoiceSettingRepository
     */
    public function __construct(InvoiceSettingRepository $invoiceSettingRepository)
    {
        $this->invoiceSettingRepository = $invoiceSettingRepository;
    }

    /**
     * Generate default invoice setting
     *
     * @param Invoice $invoice
     * @return void
     */
    public function generateDefaultSetting(Invoice $invoice): void
    {
        $this->invoiceSettingRepository->save([
            'invoice_id' => $invoice->id,
            'auto_reminder_activated' => true,
            'first_reminder_type' => InvoiceReminderSentType::InHouseUser,
            'first_reminder_days' => 0,
            'second_reminder_type' => InvoiceReminderSentType::InHouseUser,
            'second_reminder_days' => Setting::findValueOf([
                'module' => SettingModule::Invoice,
                'key' => InvoiceSettingKey::SecondReminderAfterFirstReminder
            ]),
            'third_reminder_type' => InvoiceReminderSentType::InHouseUser,
            'third_reminder_days' => Setting::findValueOf([
                'module' => SettingModule::Invoice,
                'key' => InvoiceSettingKey::ThirdReminderAfterSecondReminder
            ]),
            'debt_collector_reminder_type' => InvoiceReminderSentType::InHouseUser,
            'debt_collector_reminder_days' => Setting::findValueOf([
                'module' => SettingModule::Invoice,
                'key' => InvoiceSettingKey::SendDebtCollectorAfterThirdReminder
            ]),
        ]);
    }
}
