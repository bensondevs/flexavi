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

			// Update quotation
			$quotation = $work->quotation;
            $quotation->countAmount();
            $quotation->save();

			$this->setSuccess('Successfully save work.');
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
			$quotation = $work->quotation;
			$force ? 
				$work->forceDelete() : 
				$work->delete();

			// Update quotation
			$quotation->countAmount();
			$quotation->save();

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
