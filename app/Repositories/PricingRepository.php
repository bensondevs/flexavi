<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Pricing;

class PricingRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Pricing);
	}

	public function save(array $pricingData)
	{
		try {
			$pricing = $this->getModel();
			$pricing->fill($pricingData);
			$pricing->save();

			$this->setModel($pricing);

			$this->setSuccess('Successfully save pricing.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save pricing.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$pricing = $this->getModel();
			$force ? $pricing->forceDelete() : $pricing->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete pricing');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete pricing.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
