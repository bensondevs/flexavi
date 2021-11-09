<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Jobs\SendMail;

use App\Mail\Invoice\{
	SendInvoice,
	InvoiceFirstReminder,
	InvoiceSecondReminder,
	InvoiceThirdReminder
};

use App\Enums\Invoice\InvoiceStatus;

use App\Models\{
	Invoice,
	InvoiceItem,
	Quotation,
	Appointment,
	WorkContract
};

use App\Repositories\Base\BaseRepository;

class InvoiceRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Invoice);
	}

	public function save(array $invoiceData = [])
	{
		try {
			$invoice = $this->getModel();
			$invoice->fill($invoiceData);
			$invoice->save();

			$this->setModel($invoice->fresh());

			$this->setSuccess('Successfully save invoice.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save invoice.', $error);
		}

		return $this->getModel();
	}

	public function generateFromAppointment(Appointment $appointment, array $invoiceData = [])
	{
		if ($invoice = $appointment->invoice) {
			$this->setModel($invoice);
			$this->setSuccess('This appointment has invoice already, not generating, just returning record.');
			$this->setHttpStatusCode(200);
			return $this->getModel();
		}

		$works = $appointment->works;
		if (empty($works)) {
			$this->setUnprocessedInput('Failed to generate invoice from appointment. Appointment has no work attached.');
			return false;
		}

		try {
			$invoice = $this->getModel($invoiceData);
			$invoice->fill($invoiceData);
			$invoice->customer_id = $appointment->customer_id;
			$invoice->invoiceable_type = Appointment::class;
			$invoice->invoiceable_id = $appointment->id;
			$invoice->company_id = $appointment->company_id;
			$invoice->total = $works->sum('total');
			$invoice->save();

			// Generate items from works
			$invoice->generateItemsFromWorks($works);

			$this->setModel($invoice->fresh());

			$this->setSuccess('Successfully generate invoice from appointment');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to generate invoice from appointment.', $error);
			return false;
		}

		return $this->getModel();
	}

	public function generateFromQuotation(Quotation $quotation, array $invoiceData = [])
	{
		if ($invoice = $quotation->invoice) {
			$this->setModel($invoice);
			$this->setSuccess('This quotation has invoice already, not generating, just returning record.');
			return $this->getModel();
		}

		$works = $quotation->works;
		if (empty($works)) {
			$this->setUnprocessedInput('Failed to generate invoice from quotation. Quotation has no work attached.');
			return $this->getModel();
		}

		try {
			$invoice = $this->getModel($invoiceData);
			$invoice->invoiceable_type = Quotation::class;
			$invoice->invoiceable_id = $quotation->id;
			$invoice->company_id = $quotation->company_id;
			$invoice->customer_id = $quotation->customer_id;
			$invoice->total = $works->sum('total');
			$invoice->fill($invoiceData);
			$invoice->save();

			$invoice->refresh();

			// Generate items from works
			$invoice->generateItemsFromWorks($works);

			$this->setModel($invoice);

			$this->setSuccess('Successfully generate invoice from quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to generate invoice from quotation.', $error);
		}

		return $this->getModel();
	}

	public function generateFromWorkContract(WorkContract $contract, array $invoiceData = [])
	{
		$works = $contract->works;
		if (empty($works)) {
			$this->setUnprocessedInput('Failed to generate invoice from work contract. Work contract has no work attached.');
			return false;
		}

		try {
			$invoice = $this->getModel();
			$invoice->fill($invoiceData);
			$invoice->invoiceable_type = WorkContract::class;
			$invoice->invoiceable_id = $contract->id;
			$invoice->company_id = $contract->company_id;
			$invoice->total = $works->sum('total');
			$invoice->save();

			// Generate items from works
			$invoice->generateItemsFromWorks($works);

			$this->setModel($invoice);

			$this->setSuccess('Successfully generate invoice from work contract.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to generate invoice from work contract.', $error);
			return false;
		}

		return $this->getModel();
	}

	public function send(string $destinationEmail = '')
	{
		try {
			// Generate invoice number
			$invoice = $this->getModel();
			$invoice->status = InvoiceStatus::Sent;
			$invoice->generateNumber();
			$invoice->save();

			// Send them afterwards
			$destination = $destinationEmail ?: $invoice->customer->email;
			$mail = new SendInvoice($invoice);
			$job = new SendMail($mail, $destination);
			$job->delay(1);
			dispatch($job);

			$this->setModel($invoice);

			$this->setSuccess('Successfully send invoice to customer.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send invoice to customer', $error);
		}

		return $this->getModel();
	}

	public function printDraft()
	{
		try {
			$invoice = $this->getModel();
			$invoice->generateNumber();
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully print invoice as draft.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to print invoice as draft', $error);
		}

		return $this->getModel();
	}

	public function print()
	{
		try {
			$invoice = $this->getModel();
			$invoice->status = InvoiceStatus::Sent;
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully print invoice to customer.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to print invoice to customer', $error);
		}

		return $this->getModel();
	}

	public function overdueInvoices(array $options = [], bool $pagination = false)
	{
		if (! isset($options['scopes'])) {
			$options['scopes'] = [];
		}

		$options['scopes']['overdue'] = [];

        return $this->all($options, $pagination);
	}

	public function changeStatus(int $status)
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

	public function sendReminder(string $destinationEmail = '')
	{
		$invoice = $this->getModel();

		switch (true) {
			case ($invoice->status <= InvoiceStatus::PaymentOverdue):
				$this->sendFirstReminder($destinationEmail);
				break;

			case ($invoice->status <= InvoiceStatus::FirstReminderSent):
				$this->sendSecondReminder($destinationEmail);
				break;

			case ($invoice->status <= InvoiceStatus::SecondReminderSent):
				$this->sendThirdReminder($destinationEmail);
				break;

			default:
				$this->sendThirdReminder($destinationEmail);
				break;
		}
	}

	public function sendFirstReminder(string $destinationEmail = '')
	{
		try {
			$invoice = $this->getModel();
			$invoice->load(['items', 'customer']);

			// Send to mail
			$mail = new InvoiceFirstReminder($invoice);
			$job = new SendMail($mail, $destinationEmail ?: $invoice->customer->email);
			dispatch($job);

			$invoice->status = InvoiceStatus::FirstReminderSent;
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully send first reminder to customer.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send first reminder to customer', $error);
		}

		return $this->getModel();
	}

	public function sendSecondReminder()
	{
		try {
			$invoice = $this->getModel();
			$invoice->load(['items', 'customer']);

			// Send to mail
			$mail = new InvoiceSecondReminder($invoice);
			$job = new SendMail($mail, $destinationEmail ?: $invoice->customer->email);
			dispatch($job);

			$invoice->status = InvoiceStatus::SecondReminderSent;
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully send second reminder to customer.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send second reminder to customer', $error);
		}

		return $this->getModel();
	}

	public function sendThirdReminder()
	{
		try {
			$invoice = $this->getModel();
			$invoice->load(['items', 'customer']);

			// Send to mail
			$mail = new InvoiceThirdReminder($invoice);
			$job = new SendMail($mail, $destinationEmail ?: $invoice->customer->email);
			dispatch($job);

			$invoice->status = InvoiceStatus::ThirdReminderSent;
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully send third reminder to customer.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send third reminder to customer', $error);
		}

		return $this->getModel();
	}

	public function sendDebtCollector()
	{
		try {
			$invoice = $this->getModel();
			$invoice->status = InvoiceStatus::SentDebtCollector;
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully sent debt collector.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to send debt collector', $error);
		}
	}

	public function markAsPaid(bool $viaDebtCollector = false)
	{
		try {
			$invoice = $this->getModel();
			$invoice->status = ($viaDebtCollector) ?
				InvoiceStatus::PaidViaDebtCollector :
				InvoiceStatus::Paid;
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully mark invoice as paid.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark invoice as paid', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$invoice = $this->getModel();
			$force ?
				$invoice->forceDelete() :
				$invoice->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete invoice.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete invoice', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
