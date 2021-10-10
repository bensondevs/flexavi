<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Cost;
use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

use App\Jobs\Cost\DeleteAttachlessCosts;

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

	public function record($costable)
	{
		try {
			$costableType = get_lower_class($costable);
			$costableTypePlural = str_to_plural($costableType);

			$cost = $this->getModel();
			$cost->{$costableTypePlural}()->attach($costable);
			$cost->save();

			$this->setModel($cost);

			$this->setSuccess('Successfully record cost in ' . $costableType . '.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to record cost in ' . $costableType . '.', $error);
		}

		return $this->getModel();
	}

	public function recordMany($costable, $costs)
	{
		try {
			$costableType = get_lower_class($costable);
			$costable->costs()->syncWithoutDetaching($costs);

			$this->setSuccess('Successfully record many costs in ' . $costableType . '.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to record many costs in ' . $costableType . '.', $error);
		}

		return $this->returnResponse();
	}

	public function replaceRecord($costable, $costs)
	{
		try {
			$costableType = get_lower_class($costable);
			$costable->costs()->syncWithoutDetaching($costs);

			$this->setSuccess('Successfully replace recorded costs in ' . $costableType . '.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to replace recorded costs in ' . $costableType . '.', $error);
		}

		return $this->returnResponse();
	}

	public function unrecord($costable)
	{
		try {
			$costableType = get_lower_class($costable);
			$costableTypePlural = str_to_plural($costableType);

			$cost = $this->getModel();
			$cost->{$costableTypePlural}()->detach($costable);

			if ($cost->costables()->count() < 1) {
				$cost->delete();
			}

			$this->setSuccess('Successfully unrecord cost in ' . (string) $costableType . '.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to unrecord cost in ' . (string) $costableType . '.', $error);
		}

		return $this->returnResponse();
	}

	public function unrecordMany($costable, $costIds)
	{
		try {
			$costableType = get_lower_class($costable);
			$costableTypePlural = str_to_plural($costableType);

			$costable->costs()->detach($costIds);

			$this->setSuccess('Successfully unrecord many costs in ' . $costableType . '.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to unrecord many costs in ' . $costableType . '.', $error);
		}

		return $this->returnResponse();
	}

	public function truncate($costable)
	{
		try {
			$costableType = get_lower_class($costable);
			$costableTypePlural = str_to_plural($costableType);
			
			$costs = $costable->costs;
			$costIds = [];
			foreach ($costs as $cost) {
				$costIds[] = $cost->id;
			}
			$costable->costs()->detach();
			
			$deleteAttachlessCost = new DeleteAttachlessCosts($costIds);
			$deleteAttachlessCost->delay(1);
			dispatch($deleteAttachlessCost);

			$this->setSuccess('Successfully unrecord all costs within ' . $costableType);
		} catch (Exception $e) {
			$error = $qe->getMessage();
			$this->setError('Failed to unrecord costs within ' . $costableType);
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

	public function restore()
	{
		try {
			$cost = $this->getModel();
			$cost->restore();

			$this->setModel($cost);

			$this->setSuccess('Successfully restore cost.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore cost.', $error);
		}

		return $this->getModel();
	}
}
