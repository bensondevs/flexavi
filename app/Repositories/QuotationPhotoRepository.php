<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\QuotationPhoto;

class QuotationPhotoRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new QuotationPhoto);
	}

	public function uploadQuotationPhoto($fileRequest)
	{
		try {
			$photo = $this->getModel();
			$photo->photo = $fileRequest;

			$this->setModel($photo);

			$this->setSuccess('Successfully upload quotation photo.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete quotation photo.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function save(array $photoData)
	{
		try {
			$photo = $this->getModel();
			$photo->full($photoData);
			$photo->save();

			$this->setModel($photo);

			$this->setSuccess('Successfully save quotation photo.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save quotation photo.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$photo = $this->getModel();
			$force ? 
				$photo->forceDelete() : 
				$photo->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete quotation photo');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete quotation photo', 
				$qe->getMessage());
		}
	}
}
