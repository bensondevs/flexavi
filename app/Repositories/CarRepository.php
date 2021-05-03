<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Car;

use App\Repositories\Base\BaseRepository;

class CarRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Car);
	}

	public function companyCars(
		string $comparyId, 
		bool $freeOnly = false
	)
	{
		$cars = $this->getModel()->where('company_id', $comparyId);
		if ($freeOnly) $cars = $cars->free();
		
		return $this->setCollection($cars = $cars->get());
	}

	public function save(array $carData)
	{
		try {
			$car = $this->getModel();
			$car->fill($carData);
			$car->save();

			$this->setModel($car);

			$this->setSuccess('Successfully save car data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save car data.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function setCarImage($imageFile)
	{
		try {
			$car = $this->getModel();
			$car->car_image = $imageFile;
			$car->save();

			$this->setModel($car);

			$this->setSuccess('Successfully set car image.');
		} catch (QueryException $qe) {
			$this->setError('Failed to set car image', $qe->getMessage());
		}

		return $this->getModel();
	}

	public function delete(bool $forceDelete = false)
	{
		try {
			$car = $this->getModel();

			if ($car->status != 'free') 
				return $this->setForbidden('Failed to delete car, car in use.');

			$forceDelete ?
				$car->forceDelete() :
				$car->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete car.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete car.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
