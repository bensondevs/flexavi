<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Enums\Quotation\QuotationStatus;

use App\Models\Quotation;

use App\Repositories\Base\BaseRepository;

class QuotationRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Quotation);
	}

	public function uploadDocument($documentUpload)
	{
		try {
			$quotation = $this->getModel();
			$quotation->document = $documentUpload;

			$this->setModel($quotation);

			$this->setSuccess('Successfully upload quotation document');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete quotation document', $error);
		}

		return $this->getModel();
	}

	public function reuploadDocument($documentUpload)
	{
		try {
			$quotation = $this->getModel();
			$quotation->document = $documentUpload;
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully reupload quotation document.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to reupload quotation document.', $error);
		}

		return $this->getModel();
	}

	public function save(array $quotationData)
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($quotationData);
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully save quotation data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save quotation data.', $error);
		}

		return $this->getModel();
	}

	public function saveRevision()
	{
		try {
			$quotation = $this->getModel();
			$quotation->status = QuotationStatus::Revised;
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully revise the quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save revision.', $error);
		}

		return $this->getModel();
	}

	public function send($email = '')
	{
		try {
			$quotation = $this->getModel();
			
			// Send to email

			if ($quotation->status == 1) {
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

	public function honor(array $honorData = [])
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($honorData);
			$quotation->status = QuotationStatus::Honored;
			$quotation->honored_at = carbon()->now();
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully honor quotation data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to honor quotation data.');
		}
	}

	public function cancel(array $cancellationData)
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($cancellationData);
			$quotation->status = 5;
			$quotation->cancelled_at = carbon()->now();
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully cancel quotation data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to cancel quotation data.', $error);
		}

		return $this->getModel();
	}

	public function revise(array $revisionData = [])
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($revisionData);
			$this->setModel($quotation);
			$this->saveRevision();
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to revise quotation.', $error);
		}

		return $this->getModel();
	}

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
			$this->setError(
				'Failed to delete quotation.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
