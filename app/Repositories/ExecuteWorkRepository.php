<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Enums\Work\WorkStatus;

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

			$work = $execute->work;
			$work->status = WorkStatus::InProcess;
			$work->save();

			$this->setModel($execute);

			$this->setSuccess('Successfully execute work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to execute work.', $error);
		}

		return $this->getModel();
	}

	public function markUnfinished(array $unfinishData = [])
	{
		try {
			$execute = $this->getModel();
			$execute->is_finished = false;
			$execute->unfinish_reason = $unfinishData['unfinish_reason'];
			$execute->save();

			$work = $execute->work;
			$work->status = WorkStatus::Unfinished;
			$work->save();

			$this->setModel($execute);

			$this->setSuccess('Successfully mark execute work as unfinished.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark execute as unfinsihed.', $error);
		}

		return $this->getModel();
	}

	public function markFinished(array $finishedData = [])
	{
		try {
			$execute = $this->getModel();
			$execute->is_finished = true;
			$execute->finish_note = isset($finishedData['finish_note']) ?
				$finishedData['finish_note'] : null;
			$execute->save();

			$work = $execute->work;
			$work->status = WorkStatus::Finished;
			$work->save();

			$this->setModel($execute);

			$this->setSuccess('Successfully mark execute work as finished.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark execute work as finsihed.', $error);
		}

		return $this->getModel();
	}

	public function makeContinuation(array $continuationData = [])
	{
		try {
			$execute = $this->getModel();

			$continuation = new ExecuteWork();
			$continuation->fill($continuationData);
			$continuation->work_id = $execute->work_id;
			$continuation->previous_execute_work_id = $execute->id;
			$continuation->is_continuation = true;
			$continuation->save();

			$work = $continuation->work;
			$work->status = WorkStatus::InProcess;
			$work->save();

			$this->setModel($continuation);

			$this->setSuccess('Successfully make continuation of execute work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to make continuation of execute work.', $error);
		}

		return $this->getModel();
	}
}