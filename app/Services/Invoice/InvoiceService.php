<?php

namespace App\Services\Invoice;

use App\Enums\Invoice\InvoiceStatus;
use App\Mail\Invoice\InvoiceDebtCollectorReminder;
use App\Mail\Invoice\InvoiceFirstReminder;
use App\Mail\Invoice\InvoiceSecondReminder;
use App\Mail\Invoice\InvoiceThirdReminder;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\WorkService\WorkService;
use App\Repositories\Invoice\InvoiceItemRepository;
use App\Repositories\Invoice\InvoiceRepository;
use DB;
use Exception;

class InvoiceService
{
    const AVAILABLE_INVOICE_ACTIONS = [
        InvoiceStatus::Drafted => [
            InvoiceStatus::Sent
        ],
        InvoiceStatus::Sent => [
            InvoiceStatus::Paid,
        ],
        InvoiceStatus::PaymentOverdue => [
            InvoiceStatus::Paid, InvoiceStatus::FirstReminderSent
        ],
        InvoiceStatus::FirstReminderSent => [
            InvoiceStatus::Paid, InvoiceStatus::SecondReminderSent
        ],
        InvoiceStatus::FirstReminderOverdue => [
            InvoiceStatus::Paid, InvoiceStatus::SecondReminderSent
        ],
        InvoiceStatus::SecondReminderSent => [
            InvoiceStatus::Paid, InvoiceStatus::ThirdReminderSent
        ],
        InvoiceStatus::SecondReminderOverdue => [
            InvoiceStatus::Paid, InvoiceStatus::ThirdReminderSent
        ],
        InvoiceStatus::ThirdReminderSent => [
            InvoiceStatus::Paid, InvoiceStatus::DebtCollectorSent
        ],
        InvoiceStatus::ThirdReminderOverdue => [
            InvoiceStatus::Paid, InvoiceStatus::DebtCollectorSent
        ],
        InvoiceStatus::DebtCollectorSent => [
            InvoiceStatus::PaidViaDebtCollector
        ],
    ];

    /**
     * Invoice Repository Class Container
     *
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;

    /**
     * Invoice Item Repository Class Container
     *
     * @var InvoiceItemRepository
     */
    private InvoiceItemRepository $invoiceItemRepository;

    /**
     * Create New Service Instance
     *
     * @return void
     */
    public function __construct(
        InvoiceRepository     $invoiceRepository,
        InvoiceItemRepository $invoiceItemRepository
    )
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceItemRepository = $invoiceItemRepository;
    }

    /**
     * @param Invoice $invoice
     * @return void
     */
    public static function statusChanged(Invoice $invoice): void
    {
        if (!$invoice->wasChanged('status')) {
            return;
        }

        $reminder = $invoice->reminder;
        switch ($invoice->status) {
            case InvoiceStatus::FirstReminderSent:
                InvoiceBackgroundService::sendCustomerReminder(new InvoiceFirstReminder($invoice), $invoice);
                break;
            case InvoiceStatus::SecondReminderSent:
                InvoiceBackgroundService::sendCustomerReminder(new InvoiceSecondReminder($invoice), $invoice);
                break;
            case InvoiceStatus::ThirdReminderSent:
                InvoiceBackgroundService::sendCustomerReminder(new InvoiceThirdReminder($invoice), $invoice);
                break;
            case InvoiceStatus::DebtCollectorSent:
                InvoiceBackgroundService::sendOwnersReminder(new InvoiceDebtCollectorReminder($invoice), $invoice);
                break;
            case InvoiceStatus::PaidViaDebtCollector:
                $invoice->setPaidViaDebtCollector();
                break;
        }
    }

    /**
     * Save Invoice
     *
     * @param Invoice|null $invoice
     * @param array $invoiceData
     * @return InvoiceRepository
     * @throws Exception
     */
    public function save(Invoice $invoice = null, array $invoiceData = []): InvoiceRepository
    {
        DB::beginTransaction();
        try {

            if ($invoice) {
                $this->invoiceRepository->setModel($invoice);
                InvoiceItem::where('invoice_id', $invoice->id)->delete();
            }
            $invoice = $this->invoiceRepository->save($invoiceData['invoice_data']);

            if ($invoice->isSent()) {
                $invoice->generateNumber();
                $invoice->saveQuietly();
                InvoiceBackgroundService::send($invoice);
            }

            if ($invoice->isSent() && count($invoiceData['invoice_items']) === 0) {
                throw new Exception('Invoice items are required');
            }

            foreach ($invoiceData['invoice_items'] as $invoiceItem) {
                $this->invoiceItemRepository->destroyModel();
                $workService = WorkService::findOrFail($invoiceItem['work_service_id']);
                $invoiceItem['unit_price'] = $workService->price;
                $invoiceItem['invoice_id'] = $invoice->id;
                $invoiceItem['tax_percentage'] = $workService->tax_percentage;
                $invoiceItem['total'] = $invoiceItem['unit_price'] * $invoiceItem['amount'];
                $this->invoiceItemRepository->save($invoiceItem);
            }

            $invoice->countWorksAmount();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            $this->invoiceRepository->setError('Error saving invoice. ' . $error);
        }


        return $this->invoiceRepository;
    }

    /**
     * Resend Invoice
     *
     * @param Invoice $invoice
     * @return InvoiceRepository
     */
    public function resend(Invoice $invoice): InvoiceRepository
    {
        DB::beginTransaction();
        try {
            $this->invoiceRepository->setModel($invoice);
            $this->invoiceRepository->send();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            $this->invoiceRepository->setError('Error sending invoice. ' . $error);
        }
        return $this->invoiceRepository;
    }
}
