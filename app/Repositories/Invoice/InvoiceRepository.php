<?php

namespace App\Repositories\Invoice;

use App\Enums\Invoice\InvoiceStatus;
use App\Models\{Invoice\Invoice, Quotation\Quotation};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class InvoiceRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Invoice());
    }

    /**
     * Generate invoice from quotation
     *
     * @param Quotation $quotation
     * @param array $invoiceData
     * @return Invoice|null
     */
    public function generateFromQuotation(
        Quotation $quotation,
        array     $invoiceData = []
    )
    {
        if ($invoice = $quotation->invoice) {
            $this->setModel($invoice);
            $this->setSuccess(
                'This quotation has invoice already, not generating, just returning record.'
            );
            return $this->getModel();
        }
        $works = $quotation->works;
        if ($works->isEmpty()) {
            $this->setUnprocessedInput(
                'Failed to generate invoice from quotation. Quotation has no work attached.'
            );
            return $this->getModel();
        }
        try {
            $invoice = $this->getModel();
            $invoice->invoiceable_type = Quotation::class;
            $invoice->invoiceable_id = $quotation->id;
            $invoice->company_id = $quotation->company_id;
            $invoice->customer_id = $quotation->customer_id;
            $invoice->total = $works->sum('total');
            $invoice->fill($invoiceData);
            $invoice->save();
            $invoice->refresh();
            $this->setModel($invoice);
            $this->setSuccess('Successfully generate invoice from quotation.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to generate invoice from quotation.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Save invoice
     *
     * @param array $invoiceData
     * @return Invoice|null
     */
    public function save(array $invoiceData = []): ?Invoice
    {
        try {
            $invoice = $this->getModel();
            $invoice->fill($invoiceData);
            $invoice->save();
            if (isset($invoiceData['signature'])) {
                $invoice->clearMediaCollection('signature');
                $invoice->addMedia($invoiceData['signature'])->toMediaCollection('signature');
            }
            $this->setModel($invoice->fresh());
            $this->setSuccess('Successfully save invoice.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save invoice.', $error);
        }

        return $this->getModel();
    }

    /**
     * Send invoice to customer
     *
     * @return Invoice|null
     */
    public function send(): ?Invoice
    {
        try {
            $invoice = $this->getModel();
            if ($invoice->status <= InvoiceStatus::Sent) {
                $invoice->status = InvoiceStatus::Sent;
                $invoice->sent_at = now();
            }
            $invoice->save();
            $this->setModel($invoice);
            $this->setSuccess('Successfully send invoice to customer.');
        } catch (QueryException $e) {
            $error = $e->getMessage();
            $this->setError('Failed to send invoice to customer', $error);
        }

        return $this->getModel();
    }

    /**
     * Print invoice as draft and generate invoice number
     *
     * @return Invoice|null
     */
    public function printDraft(): ?Invoice
    {
        try {
            $invoice = $this->getModel();
            $invoice->generateNumber();
            $invoice->save();
            $this->setModel($invoice);
            $this->setSuccess('Successfully print invoice as draft.');
        } catch (QueryException $e) {
            $error = $e->getMessage();
            $this->setError('Failed to print invoice as draft', $error);
        }

        return $this->getModel();
    }

    /**
     * Print invoice and set the status as sent
     *
     * @return Invoice|null
     */
    public function print(): ?Invoice
    {
        try {
            $invoice = $this->getModel();

            if ($invoice->isDrafted()) {
                $invoice->status = InvoiceStatus::Sent;
                $invoice->saveQuietly();
            }
            $this->setModel($invoice);
            $this->setSuccess('Successfully print invoice to customer.');
        } catch (QueryException $e) {
            $error = $e->getMessage();
            $this->setError('Failed to print invoice to customer', $error);
        }

        return $this->getModel();
    }

    /**
     * Change status of the invoice
     *
     * @param int $status
     * @return Invoice|null
     */
    public function changeInvoiceStatus(int $status): ?Invoice
    {
        try {
            $invoice = $this->getModel();
            $invoice->status = $status;
            $invoice->save();
            $this->setModel($invoice);
            $this->setSuccess('Successfully change invoice status.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to change invoice status.', $error);
        }

        return $this->getModel();
    }

    /**
     * Mark invoice as paid
     *
     * Set the parameter to true if payment settled through debt collector
     *
     * @return Invoice|null
     */
    public function markAsPaid(): ?Invoice
    {
        try {
            $invoice = $this->getModel();
            $invoice->status = InvoiceStatus::Paid;
            $invoice->save();
            $this->setModel($invoice);
            $this->setSuccess('Successfully mark invoice as paid.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to mark invoice as paid', $error);
        }

        return $this->getModel();
    }

    /**
     * Restore soft-deleted invoice
     *
     * @return Invoice|null
     */
    public function restore(): ?Invoice
    {
        try {
            $invoice = $this->getModel();
            $invoice->restore();
            $this->setModel($invoice);
            $this->setSuccess('Successfully restore invoice');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore invoice', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete invoice, set parameter to true for force delete
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $invoice = $this->getModel();
            $force ? $invoice->forceDelete() : $invoice->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete invoice.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete invoice', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Change status invoice
     *
     * @param int $status
     * @return Invoice|null
     */
    public function changeStatus(int $status): ?Invoice
    {
        try {
            $invoice = $this->getModel();
            $invoice->status = $status;
            if ($status === InvoiceStatus::Sent) {
                $invoice->sent_at = now();
            }

            if (in_array($status, [InvoiceStatus::Paid, InvoiceStatus::PaidViaDebtCollector])) {
                $invoice->paid_at = now();
            }
            $invoice->save();
            $this->setModel($invoice);
            $this->setSuccess('Successfully change invoice status.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to change invoice status.', $error);
        }

        return $this->getModel();
    }
}
