<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Pricing;

class PricingRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Pricing);
	}

	/**
	 * Save pricing
	 * 
	 * @param  array  $pricingData
	 * @return \App\Models\Pricing
	 */
	public function save(array $pricingData)
	{
		try {
			$pricing = $this->getModel();
			$pricing->fill($pricingData);
			$pricing->save();

			$this->setModel($pricing);

			$this->setSuccess('Successfully save pricing.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save pricing.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete pricing
	 * 
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$pricing = $this->getModel();
			$force ? $pricing->forceDelete() : $pricing->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete pricing');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete pricing.', $error);
		}

		return $this->returnResponse();
	}
}
