<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\WorkContract;

class WorkContractRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new WorkContract);
	}

	/**
	 * Upload contract pdf
	 * 
	 * @param  mixed  $fileRequest
	 * @return \App\Models\WorkContract
	 */
	public function uploadContractPdf($fileRequest)
	{
		try {
			$contract = $this->getModel();
			$contract->pdf_url = $fileRequest;

			$this->setModel($contract);

			$this->setSuccess('Successfully upload contract PDF file');
		} catch (Exception $e) {
			$this->setError('Failed to upload', $e->getMessage());
		}

		return $this->getModel();
	}

	/**
	 * Save for create or update work contract
	 * 
	 * @param  array  $contractData
	 * @return \App\Models\WorkContract
	 */
	public function save(array $contractData)
	{
		try {
			$contract = $this->getModel();
			$contract->fill($contractData);
			$contract->save();

			$this->setModel($contract);

			$this->setSuccess('Successfully save work contract.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save work contract.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete work contract
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$contract = $this->getModel();
			$force ?
				$contract->forceDelete() :
				$contract->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete work contract.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete work contract.', $error);
		}

		return $this->returnResponse();
	}
}
