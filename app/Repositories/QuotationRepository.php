<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

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
			$this->setError(
				'Failed to delete quotation document',
				$qe->getMessage()
			);
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
			$quotation->status = 3;
			$quotation->save();

			$this->setModel($quotation);
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save revision.', $error);
		}

		return $this->getModel();
	}

	public function honor()
	{
		try {
			$quotation = $this->getModel();
			$quotation->status = 4;
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
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully cancel quotation data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to cancel quotation data.', $error);
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
