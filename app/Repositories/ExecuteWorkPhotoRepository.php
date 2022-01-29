<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\ExecuteWorkPhoto;
use App\Enums\ExecuteWorkPhoto\PhotoConditionType as Type;
use App\Jobs\ExecuteWorkPhoto\UploadMultiplePhoto;

class ExecuteWorkPhotoRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new ExecuteWorkPhoto);
	}

	/**
	 * Populate before-work photos
	 * 
	 * @return \Illuminate\Support\Collection|
	 * 		   \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function beforeWorkPhotos()
	{
		$photos = $this->getCollection();

		$type = Type::Before;
		return $photos->where('photo_condition_type', $type)->values();
	}

	/**
	 * Populate after-work photos
	 * 
	 * @return \Illuminate\Support\Collection|
	 * 		   \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function afterWorkPhotos()
	{
		$photos = $this->getCollection();

		$type = Type::After;
		return $photos->where('photo_condition_type', $type)->values();
	}

	/**
	 * Upload photo for execute work
	 * 
	 * @param  array  $photoData
	 * @return \App\Models\ExecuteWorkPhoto
	 */
	public function uploadPhoto(array $photoData = [])
	{
		try {
			$photo = $this->getModel();
			$photo->photo = $photoData['photo'];
			unset($photoData['photo']);
			$photo->fill($photoData);
			$photo->save();

			$this->setModel($photo);

			$this->setSuccess('Successfully upload execute work photo.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to upload execute work photo.');
		}

		return $this->getModel();
	}

	/**
	 * Upload multiple photo
	 * 
	 * @param  array  $photoDataArray
	 * @return bool
	 */
	public function uploadMultiplePhoto(array $photoDataArray = [])
	{
		try {
			$uploadJob = new UploadMultiplePhoto($photoDataArray);
			dispatch($uploadJob);

			$this->setSuccess('Successfully upload multiple photo.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to upload multiple photo.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Delete execute work photo
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$photo = $this->getModel();
			$force ?
				$photo->forceDelete() :
				$photo->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete execute work photo.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete execute work photo.', $error);
		}

		return $this->returnResponse();
	}
}
