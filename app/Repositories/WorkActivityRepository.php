<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\WorkActivity;

class WorkActivityRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new WorkActivity);
	}

	public function save(array $activityData)
	{
		try {
			$activity = $this->getModel();
			$activity->fill($activityData);
			$activity->save();

			$this->setModel($activity);

			$this->setSuccess('Successfully save work activity.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save work activity.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force)
	{
		try {
			$activity = $this->getModel();
			$force ? 
				$activity->forceDelete() : 
				$activity->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete work activity');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete work activity', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
