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
			$error = $qe->getMessage();
			$this->setError('Failed to save warranty.', $error);
		}

		return $this->getModel();
	}

	public function storeMultiple(array $rawWarranties = [])
	{
		try {
			Warranty::insert($rawWarranties);

			$this->setSuccess('Successfully store multiple works as warranties.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to store multiple works as warranties.', $error);
		}

		return $this->returnResponse();
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
			$error = $qe->getMessage();
			$this->setError('Failed to delete warranty.', $error);
		}

		return $this->returnResponse();
	}
}
