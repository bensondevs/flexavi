<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Work;

class WorkRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Work);
	}

	public function save(array $workData)
	{
		try {
			$work = $this->getModel();
			$work->fill($workData);
			$work->save();

			$this->setModel($work);

			$this->setSuccess('Successfully created work.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to create work.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$work = $this->getModel();
			$force ? 
				$work->forceDelete() : 
				$work->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete work.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete work.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
