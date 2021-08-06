<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Cost;

class CostRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Cost);
	}

	public function save(array $costData = [])
	{
		try {
			$cost = $this->getModel();
			$cost->fill($costData);
			$cost->save();

			$this->setModel($cost);

			$this->setSuccess('Successfully save cost.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save cost.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$cost = $this->getModel();
			$force ?
				$cost->forceDelete() :
				$cost->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete cost.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete cost.', $error);
		}

		return $this->returnResponse();
	}
}
