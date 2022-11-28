<?php

namespace App\Services\Invoice;

use App\Jobs\Invoice\Reminder\CustomerReminder;
use App\Jobs\Invoice\Reminder\OwnerReminder;
use App\Mail\Invoice\{InvoiceDebtCollectorReminder, InvoiceFirstReminder, InvoiceSecondReminder, InvoiceThirdReminder};
use App\Models\Invoice\Invoice;
use App\Repositories\Invoice\InvoiceReminderRepository;

class InvoiceQueueService
{

    /**
     * InvoiceSettingRepository instance.
     *
     * @var InvoiceReminderRepository
     */
    private InvoiceReminderRepository $invoiceReminderRepository;


    public function __construct(
        InvoiceReminderRepository $invoiceReminderRepository,
    )
    {
        $this->invoiceReminderRepository = $invoiceReminderRepository;
    }

    /**
     * Handle the job.
     *
     * @param array $payload
     *
     * @return void
     */
    public function handle(array $payload): void
    {
        // Return type object
        $command = unserialize($payload['data']['command']);

        $jobClass = get_class($command);
        $mailableClass = get_class($command->mailable);
        $invoiceId = $command->invoice->id;

        if (CustomerReminder::class === $jobClass) {
            $this->handleCustomerReminder($invoiceId, $mailableClass);
        }

        if (OwnerReminder::class === $jobClass) {
            $this->handleOwnerReminder($invoiceId, $mailableClass);
        }
    }


    /**
     * Handle the customer reminder.
     *
     * @param string $invoiceId
     * @param string $mailableClass
     * @return void
     */
    private function handleCustomerReminder(string $invoiceId, string $mailableClass): void
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $reminder = $invoice->reminder;
        $this->invoiceReminderRepository->setModel($reminder);

        switch ($mailableClass) {
            case InvoiceFirstReminder::class:
                $this->invoiceReminderRepository->save([
                    'customer_first_reminder_sent_at' => now(),
                ]);
                break;
            case InvoiceSecondReminder::class:
                $this->invoiceReminderRepository->save([
                    'customer_second_reminder_sent_at' => now(),
                ]);
                break;
            case InvoiceThirdReminder::class:
                $this->invoiceReminderRepository->save([
                    'customer_third_reminder_sent_at' => now(),
                ]);
                break;
            case InvoiceDebtCollectorReminder::class:
                $this->invoiceReminderRepository->save([
                    'customer_sent_to_debt_collector_sent_at' => now(),
                ]);
                break;

        }

    }

    /**
     * Handle the owner reminder.
     *
     * @param string $invoiceId
     * @param string $mailableClass
     * @return void
     */
    private function handleOwnerReminder(string $invoiceId, string $mailableClass): void
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $reminder = $invoice->reminder;
        $this->invoiceReminderRepository->setModel($reminder);

        switch ($mailableClass) {
            case InvoiceFirstReminder::class:
                $this->invoiceReminderRepository->save([
                    'user_first_reminder_sent_at' => now(),
                ]);
                break;
            case InvoiceSecondReminder::class:
                $this->invoiceReminderRepository->save([
                    'user_second_reminder_sent_at' => now(),
                ]);
                break;
            case InvoiceThirdReminder::class:
                $this->invoiceReminderRepository->save([
                    'user_third_reminder_sent_at' => now(),
                ]);
                break;
            case InvoiceDebtCollectorReminder::class:
                $this->invoiceReminderRepository->save([
                    'user_sent_to_debt_collector_sent_at' => now(),
                ]);
                break;

        }
    }


}
