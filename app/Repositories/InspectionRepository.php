<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Inspection;

use App\Repositories\Base\BaseRepository;

class InspectionRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Inspection);
	}

	public function save(array $inspectionData)
	{
		try {
			$inspection = $this->getModel();
			$inspection->fill($inspectionData);
			$inspection->save();

			$this->setModel($inspection);

			$this->setSuccess('Successfully save inspection data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save inspection data.',
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force)
	{
		try {
			$inspection = $this->getModel();
			$force ?
				$inspection->forceDelete() :
				$inspection->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete inspection.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete inspection.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
