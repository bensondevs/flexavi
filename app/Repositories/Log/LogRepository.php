<?php

namespace App\Repositories\Log;

use App\Models\Log\Log;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LogRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Log);
	}

	/**
	 * Repository constructor method
	 *
	 * @return mixed
	 */
	public function groupByDateAndHour()
	{
		$groupByDateAndHour = function ($items) {
			return collect($items)->groupBy(
				fn ($item) => carbon()->parse(
					arrayobject_accessor($item, "created_at")
				)->toDateString()
			)->map(
				fn ($date) => $date->groupBy(
					fn ($item) => carbon()->parse(
						arrayobject_accessor($item, "created_at")
					)->format("H")
				)
			);
		};

		if ($pagination = $this->getPagination()) {
			$paginationData = $pagination instanceof LengthAwarePaginator ? $pagination->toArray()['data'] : $pagination['data'];
			$pagination = collect($pagination)->replace([
				'data' => $groupByDateAndHour($paginationData)->toArray()
			]);
			$this->setCollection(collect($pagination['data']));
			return $this->setPagination($pagination);
		}

		$collection = $this->getCollection();
		$collection = $groupByDateAndHour($collection->toArray());
		return $this->setCollection($collection);
	}

	/**
	 * Restore Logs
	 *
	 * @return Collection|array|null
	 */
	public function restoreMany()
	{
		try {
			$logs = $this->getCollection();
			$logs = $logs->each->restore();
			$this->setCollection($logs);
			$this->setSuccess('Successfully restore logs.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore logs.', $error);
		}

		return $this->getCollection();
	}

	/**
	 * Delete Logs
	 *
	 * @param bool $force
	 * @return bool
	 */
	public function deleteMany(bool $force = false)
	{
		try {
			$logs = $this->getCollection();
			$force ? $logs->each->forceDelete() : $logs->each->delete();
			$this->destroyCollection();
			$this->setSuccess('Successfully delete logs.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete logs.', $error);
		}

		return $this->returnResponse();
	}
}
