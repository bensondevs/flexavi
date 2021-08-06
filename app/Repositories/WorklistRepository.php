<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Worklist;

class WorklistRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Worklist);
	}

	public function save(array $worklistData = [])
	{
		try {
			$worklist = $this->getModel();
			$worklist->fill($worklistData);
			$worklist->save();

			$this->setModel($worklist);

			$this->setSuccess('Successfully save worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save worklist.', $error);
		}

		return $this->getModel();
	}

	public function process()
	{
		try {
			$worklist = $this->getModel();
			$worklist->process();

			$this->setModel($worklist);

			$this->setSuccess('Successfully process worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to process worklist.', $error);
		}

		return $this->getModel();
	}

	public function calculate()
	{
		try {
			$worklist = $this->getModel();
			$worklist->calculate();

			$this->setModel($worklist);

			$this->setSuccess('Successfully calculate worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to calculate worklist.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$worklist = $this->getModel();
			$force ?
				$worklist->forceDelete() :
				$worklist->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete worklist.', $error);
		}

		return $this->returnResponse();
	}
}
