<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Enums\Work\WorkStatus;

use App\Models\Work;
use App\Models\ExecuteWork;

class ExecuteWorkRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new ExecuteWork);
	}

	public function execute(array $executionData = [])
	{
		try {
			$execute = $this->getModel();
			$execute->fill($executionData);
			$execute->save();

			$this->setModel($execute);

			$this->setSuccess('Successfully execute work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to execute work.', $error);
		}

		return $this->getModel();
	}

	public function finish(array $finishData = [])
	{
		try {
			$execute = $this->getModel();
			$execute->finish($finishData);

			$this->setModel($execute);

			$this->setSuccess('Successfully finish execute work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to finish execute work.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$execute = $this->getModel();
			$force ?
				$execute->forceDelete() :
				$execute->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete execute work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete execute work.', $error);
		}

		return $this->returnResponse();
	}
}