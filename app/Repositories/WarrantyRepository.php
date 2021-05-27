<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Warranty;

class WarrantyRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Warranty);
	}

	public function save(array $warrantyData)
	{
		try {
			$warranty = $this->getModel();
			$warranty->fill($warrantyData);
			$warranty->save();

			$this->setModel($warranty);

			$this->setSuccess('Successfully save warranty.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save warranty.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$warranty = $this->getModel();
			$force ? 
				$warranty->forceDelete() : 
				$warranty->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete warranty.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete warranty.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
