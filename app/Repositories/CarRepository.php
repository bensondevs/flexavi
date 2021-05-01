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

			$this->setSuccess('Successfully save new car.');
		} catch (QueryException $qe) {
			$this->setError('Failed to save new car.');
		}
	}

	public function delete(bool $forceDelete = false)
	{
		try {
			$car = $this->getModel();
			$forceDelete ?
				$car->forceDelete() :
				$car->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete car.');
		} catch (QueryException $qe) {
			$this->setError('Failed to delete car.');
		}
	}
}
