<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\WorkConditionPhoto;

class WorkConditionPhotoRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new WorkConditionPhoto);	
	}

	public function uploadConditionPhoto($fileRequest)
	{
		try {
			$photo = $this->getModel();
			$photo->photo = $fileRequest;

			$this->setModel($photo);

			$this->setSuccess('Successfully upload photo.');
		} catch (Exception $e) {
			$this->setError(
				'Failed to upload photo.', 
				$e->getMessage()
			);
		}

		return $this->getModel();
	}

	public function save(array $photoData)
	{
		try {
			$photo = $this->getModel();
			$photo->fill($photoData);
			$photo->save();

			$this->setModel($photo);

			$this->setSuccess('Successfully save work condition photo');
		} catch (QueryException $qe) {
			$this->setError('Failed to save work condition photo', $qe->getMessage());
		}
	}

	public function delete(bool $force = false)
	{
		try {
			$photo = $this->getModel();
			$force ?
				$photo->forceDelete() :
				$photo->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete work condition photo');
		} catch (QueryException $qe) {
			$this->setError('Failed to delete work condition photo', $qe->getMessage());
		}

		return $this->returnResponse();
	}
}
