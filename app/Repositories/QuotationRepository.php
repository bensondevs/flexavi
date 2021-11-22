<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Enums\Quotation\QuotationStatus;
use App\Models\{ Quotation, QuotationAttachment };
use App\Mail\Quotation\{ QuotationMail, NotifyQuotationRevision };
use App\Jobs\SendMail;

class QuotationRepository extends BaseRepository
{
	/**
	 * Repository class constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Quotation);
	}

	/**
	 * Save quotation
	 * 
	 * @param array  $quotationData
	 * @return \App\Models\Quotation
	 */
	public function save(array $quotationData)
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($quotationData);
			$quotation->damage_causes = $quotationData['damage_causes'];
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully save quotation data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save quotation data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add attachment to quotation
	 * 
	 * @param array  $attachmentData
	 * @return \App\Models\Appointment
	 */
	public function addAttachment(array $attachmentData = [])
	{
		try {
			$quotation = $this->getModel();
			
			$attachment = new QuotationAttachment();
			$attachment->attachment_file = $attachmentData['attachment'];
			unset($attachmentData['attachment']);
			$attachment->fill($attachmentData);
			$attachment->quotation_id = $quotation->id;
			$attachment->company_id = $quotation->company_id;
			$attachment->save();

			$this->setSuccess('Successfully add attachment to quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add attachment to quotation.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Remove quotation attachment
	 * 
	 * @param \App\Models\QuotationAttachment  $attachment
	 * @return bool
	 */
	public function removeAttachment(QuotationAttachment $attachment)
	{
		try {
			// Delete file
			$attachment->delete();

			$this->setSuccess('Successfully remove attachment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to remove attachment', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Send quotation through email and  
	 * set the status of quotation \App\Enums\Quotation\QuotationStatus::Sent
	 * 
	 * @param array  $sentData
	 * @return \App\Models\Quotation
	 */
	public function send(array $sendData = [])
	{
		try {
			$quotation = $this->getModel();
			
			// Prepare data
			$destination = $quotation->customer->email;
			if (isset($sendData['destination'])) {
				$destination = $sendData['destination'];
			}
			$text = $quotation->quotation_description;
			if (isset($sendData['text'])) {
				$text = $sendData['text'];
			}

			// Send email to customer
			$mail = new QuotationMail($quotation, $text);
			$send = new SendMail($mail, $destination);
			dispatch($send);

			if ($quotation->status == QuotationStatus::Draft) {
				$quotation->status = QuotationStatus::Sent;
				$quotation->save();
			}

			$this->setModel($quotation);

			$this->setSuccess('Successfully send quotation to customer.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed send quotation to customer.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Return quotation data and set the status of quotation to
	 * \App\Enums\Quotation\QuotationStatus::Sent
	 * 
	 * @return \App\Models\Quotation
	 */
	public function print()
	{
		try {
			$quotation = $this->getModel();
			if ($quotation->status == 1) {
				$quotation->status = QuotationStatus::Sent;
				$quotation->save();
			}
			
			$this->setModel($quotation);

			$this->setSuccess('Successfully print quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to print quotation.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Revise quotation and set the status of quotation to
	 * \App\Enums\Quotation\QuotationStatus::Revised
	 * 
	 * @param array  $revisionData
	 * @return \App\Models\Quotation
	 */
	public function revise(array $revisionData = [])
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($revisionData);
			$quotation->status = QuotationStatus::Revised;
			$quotation->save();

			// Inform Customer
			$customer = $quotation->customer;
			$destination = $customer->email ?: $revisionData['inform_email'];
			$mail = new NotifyQuotationRevision($quotation);
			$send = new SendMail($mail, $destination);
			dispatch($send);

			$this->setModel($quotation);

			$this->setSuccess('Successfully revise the quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to revise quotation.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Honor the quotation and set the status of quotation to
	 * \App\Enums\Quotation\QuotationStatus::Honored
	 * 
	 * @param array  $honorData
	 * @return \App\Models\Quotation
	 */
	public function honor(array $honorData = [])
	{
		try {
			$quotation = $this->getModel();
			$quotation->status = QuotationStatus::Honored;
			$quotation->honored_at = carbon()->now();
			if (isset($honorData['discount_amount'])) {
				$quotation->discount_amount = $honorData['discount_amount'];
				$quotation->countWorksAmount();
			}
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully honor quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to honor quotation.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Cancel quotation and set the status of quotation to
	 * \App\Enums\Quotation\QuotationStatus::Cancelled
	 * 
	 * @param array  $cancellationData
	 * @return \App\Models\Quotation
	 */
	public function cancel(array $cancellationData = [])
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($cancellationData);
			$quotation->status = QuotationStatus::Cancelled;
			$quotation->cancelled_at = carbon()->now();
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully cancel quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to cancel quotation.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete or force delete quotation
	 * To do force delete, set the parameter to TRUE
	 * 
	 * @param bool $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$quotation = $this->getModel();
			$force ?
				$quotation->forceDelete() :
				$quotation->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete quotation.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Restore soft-deleted quotation
	 * 
	 * @return \App\Models\Quotation
	 */
	public function restore()
	{
		try {
			$quotation = $this->getModel();
			$quotation->restore();
			$this->setModel($quotation);

			$this->setSuccess('Successfully restore quotation');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore quotation', $error);
		}

		return $this->getModel();
	}
}
