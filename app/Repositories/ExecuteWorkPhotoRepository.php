<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Enums\PhotoConditionType;

use App\Models\ExecuteWorkPhoto;

use App\Jobs\ExecuteWorkPhoto\UploadMultiplePhoto;

class ExecuteWorkPhotoRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new ExecuteWorkPhoto);
	}

	public function beforeWorkPhotos()
	{
		$photos = $this->getCollection();

		$type = PhotoConditionType::Before;
		return $photos->where('photo_condition_type', $type);
	}

	public function afterWorkPhotos()
	{
		$photos = $this->getCollection();

		$type = PhotoConditionType::After;
		return $photos->where('photo_condition_type', $type);
	}

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

	public function delete()
	{
		try {
			$photo = $this->getModel();
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
