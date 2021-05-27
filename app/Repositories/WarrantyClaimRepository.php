<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\WarrantyClaim;

class WarrantyClaimRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new WarrantyClaim);
	}

	public function save(array $warrantyData)
	{
		try {
			$claim = $this->getModel();
			$claim->fill($warrantyData);
			$claim->save();

			$this->setModel($claim);

			$this->setSuccess('Successfully save warranty claim.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save warranty claim.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$cliam = $this->getModel();
			$force ? 
				$claim->forceDelete() : 
				$claim->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete warranty claim.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete warranty claim.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
