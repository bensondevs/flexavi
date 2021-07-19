<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Jobs\SendMail;

use App\Mail\Invoice\SendInvoice;

use App\Enums\Invoice\InvoiceStatus;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Quotation;
use App\Models\Appointment;
use App\Models\WorkContract;

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

			$this->setModel($invoice);

			$this->setSuccess('Successfully save invoice.');
		} catch (QueryException $e) {
			$error = $qe->getMessage();
			$this->setError('Failed to save invoice.', $error);
		}

		return $this->getModel();
	}

	public function generateFromAppointment(Appointment $appointment, array $invoiceData = [])
	{
		$works = $appointment->works;
		if (empty($works)) {
			$this->setUnprocessedInput('Failed to generate invoice from appointment. Appointment has no work attached.');
			return false;
		}

		try {
			$invoice = $this->getModel();
			$invoice->fill($invoiceData);
			$invoice->referenceable_type = Appointment::class;
			$invoice->referenceable_id = $appointment->id;
			$invoice->company_id = $appointment->company_id;
			$invoice->total = $works->sum('total');
			$invoice->save();

			// Generate items from works
			$invoice->generateItemsFromWorks($works);

			$this->setModel($invoice);

			$this->setSuccess('Successfully generate invoice from appointment');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to generate invoice from appointment.', $error);
			return false;
		}

		return $invoice;
	}

	public function generateFromQuotation(Quotation $quotation, array $invoiceData = [])
	{
		$works = $quotation->works;
		if (empty($works)) {
			$this->setUnprocessedInput('Failed to generate invoice from quotation. Quotation has no work attached.');
			return false;
		}

		try {
			$invoice = $this->getModel();
			$invoice->fill($invoiceData);
			$invoice->referenceable_type = Quotation::class;
			$invoice->referenceable_id = $quotation->id;
			$invoice->company_id = $quotation->company_id;
			$invoice->total = $works->sum('total');
			$invoice->save();

			// Generate items from works
			$invoice->generateItemsFromWorks($works);

			$this->setModel($invoice);

			$this->setSuccess('Successfully generate invoice from quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to generate invoice from quotation.', $error);
			return false;
		}

		return $invoice;
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
			$invoice->referenceable_type = WorkContract::class;
			$invoice->referenceable_id = $contract->id;
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

		return $invoice;
	}

	public function send()
	{
		try {
			$invoice = $this->getModel();
			$invoice->status = InvoiceStatus::Sent;
			// $invoice->generateNumber();
			$invoice->save();

			// Generate Invoice Number in background
			dispatch(new GenerateInvoiceNumber($invoice));

			// Send them afterwards
			$mail = new SendInvoice($invoice);
			$job = new SendEmail($mail, $invoice->customer->email);
			$jon->delay(1);
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

		array_push($options['scopes'], 'overdue');

        return $this->all($options, $pagination);
	}

	public function changeStatus($status)
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

	public function sendFirstReminder($nextDueDate)
	{
		try {
			$invoice = $this->getModel();
			$invoice->load(['items', 'customer']);

			// Send to mail
			$mail = new InvoiceFirstReminder($invoice);
			$job = new SendEmail($mail, $invoice->customer->email);
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
			$job = new SendEmail($mail, $invoice->customer->email);
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
			$job = new SendEmail($mail, $invoice->customer->email);
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
