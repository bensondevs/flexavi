<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\{ 
	Car, 
	Employee, 
	Worklist,
	CarRegisterEmployee, 
	CarRegisterTime 
};

use App\Enums\Car\CarStatus;

use App\Repositories\Base\BaseRepository;

class CarRepository extends BaseRepository
{
	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Car);
	}

	/**
	 * Populate free cars in the company.
	 * 
	 * @param array  $options
	 * @return mixed
	 */
	public function freeCars(array $options = [])
	{
		array_push($options['wheres'], [
			'column' => 'status',
			'value' => CarStatus::Free,
		]);

		return $this->all($options);
	}

	/**
	 * Save car.
	 * 
	 * @param array  $carData
	 * @return \App\Models\Car  $car
	 */
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

	/**
	 * Validate car insurance.
	 * 
	 * @param array  $carData
	 * @return \App\Models\Car  $car
	 */
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

	/**
	 * Set image for car.
	 * 
	 * @param file  $imageFile
	 * @return \App\Models\Car  $car
	 */
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

	/**
	 * Delete car.
	 * 
	 * @param array  $forceDelete
	 * @return \App\Models\Car  $car
	 */
	public function delete(bool $forceDelete = false)
	{
		try {
			$car = $this->getModel();

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

	/**
	 * Restore car.
	 * 
	 * @param array  $carData
	 * @return \App\Models\Car  $car
	 */
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
