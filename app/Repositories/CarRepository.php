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

	public function freeCars(array $options = [])
	{
		array_push($options['wheres'], [
			'column' => 'status',
			'value' => 'free',
		]);

		return $this->all($options);
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
			$error = $qe->getMessage();
			$this->setError('Failed to save car data.', $error);
		}

		return $this->getModel();
	}

	public function validateInsurance()
	{
		try {
			$car = $this->getModel();
			$car->validateInsurance();
			$car->save();

			$this->setModel($car);

			$this->setSuccess('Successfully validate car insurance');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to validate car insurance', $error);
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
			$error = $qe->getMessage();
			$this->setError('Failed to set car image', $error);
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

			$this->setSuccess('Successfully ' . ($forceDelete ? 'permanent ' : '') . 'delete car.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete car.', $error);
		}

		return $this->returnResponse();
	}

	public function restore()
	{
		try {
			$car = $this->getModel();
			$car->restore();

			$this->setModel($car);

			$this->setSuccess('Successfully restore car.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore car.', $error);
		}

		return $this->getModel();
	}
}
