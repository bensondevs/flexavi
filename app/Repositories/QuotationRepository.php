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

	public function save(array $quotationData)
	{
		try {
			$quotation = $this->getModel();
			$quotation->fill($quotationData);
			$quotation->save();

			$this->setModel($quotation);

			$this->setSuccess('Successfully save quotation data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save quotation data.', 
				$qe->getMessage()
			);
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
