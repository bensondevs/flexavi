<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Warranty;

class WarrantyRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Warranty);
	}

	/**
	 * Save warranty
	 * 
	 * @param  array  $warrantyData
	 * @return \App\Models\Warranty
	 */
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

	/**
	 * Select works to be attached to warranty
	 * 
	 * @param  array  $workIds
	 * @return \App\Models\Warranty
	 */
	public function selectWorks(array $workIds)
	{
		try {
			$warranty = $this->getModel();

			//

		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$message = 'Failed to select works to be attached to warranty.';
			$this->setError($message, $error);
		}

		return $this->getModel();
	}

	/**
	 * Attach a work to the warranty
	 * 
	 * @param  \App\Models\Work $work
	 * @param  array  $extra
	 * @return \App\Models\Warranty
	 */
	public function attachWork(Work $work, array $extras = [])
	{
		try {
			$warranty = $this->getModel();

			$warrantyWork = WarrantyWork::create(array_merge([
				'warranty_id' => $warranty->id,
				'work_id' => $work->id,
			], $extras));

			$this->setSuccess('Successfully attach work to warranty.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to attach work to warranty.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Update warranty work
	 * 
	 * @param  \App\Models\WarrantyWork $warrantyWork
	 * @param  array  $data
	 * @return bool
	 */
	public function updateWarrantyWork()
	{
		//
	}

	/**
	 * Detach a work from the warranty
	 * 
	 * @param  \App\Models\WarrantyWork $warrantyWork
	 */
	public function detachWork()
	{
		//
	}

	/**
	 * Delete warranty
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
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
