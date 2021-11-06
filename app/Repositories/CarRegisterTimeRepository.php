<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\{ Car, CarRegisterTime };

class CarRegisterTimeRepository extends BaseRepository
{
	/**
     * Parent model
     * 
     * \App\Models\Car  $car 
     */
	private $car;

	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new CarRegisterTime);
	}

	/**
     * Set parent model
     * 
     * @param \App\Models\Car  $car 
     * @return  void
     */
	public function setCar(Car $car)
	{
		$this->car = $car;
	}

	/**
     * Get parent model
     *  
     * @return  void
     */
	public function getCar()
	{
		return $this->car;
	}

	/**
	 * Create register time to register time to car.
	 * 
	 * @param array  $timeData
	 * @return \App\Models\CarRegisterTime  mixed
	 */
	public function register(array $timeData = [])
	{
		try {
			$car = $this->getCar();
			$timeData['car_id'] = $car->id;
			$registerTime = new CarRegisterTime($timeData);
			$registerTime->save();

			$this->setModel($registerTime);

			$this->setSuccess('Successfully register time to a car.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to register time to a car.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Register car to a worklist time hour
	 * 
	 * @param \App\Models\Worklist  $worklist
	 * @return \App\Models\CarRegisterTime  mixed
	 */
	public function registerWorklist(Worklist $worklist)
	{
		try {
			$car = $this->getModel();
			$registerTime = new CarRegisterTime([
				'company_id' => $worklist->company_id,
				'worklist_id' => $worklist->id,
				'car_id' => $car->id,
				'should_out_at' => $worklist->start_time,
				'should_return_at' => $worklist->end_time,
			]);
			$registerTime->save();

			$this->setModel($registerTime);

			$this->setSuccess('Successfully register car to a worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to register car to worklist time.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Mark car as out and mark the registered time.
	 * 
	 * @return \App\Models\CarRegisterTime  mixed
	 */
	public function markOut()
	{
		try {
			$registerTime = $this->getModel();
			$registerTime->marked_out_at = now();
			$registerTime->save();

			$this->setModel($registerTime);

			$this->setSuccess('Successfully mark car as out.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark car as out.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Mark car as returned and mark the registered time.
	 * 
	 * @return \App\Models\CarRegisterTime  mixed
	 */
	public function markReturn()
	{
		try {
			$registerTime = $this->getModel();
			$registerTime->marked_return_at = now();
			$registerTime->save();

			$this->setModel($registerTime);

			$this->setSuccess('Successfully mark car as returned.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark car as returned.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Update registered time to apply changes.
	 * 
	 * @param array  $timeData
	 * @return \App\Models\CarRegisterTime  mixed
	 */
	public function update(array $timeData = [])
	{
		try {
			$registerTime = $this->getModel();
			$registerTime->fill($timeData);
			$registerTime->save();

			$this->setModel($registerTime);

			$this->setSuccess('Successfully update car register time.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to update car register time.');
		}

		return $this->getModel();
	}

	/**
	 * Delete registered time of a car.
	 * 
	 * @param bool  $force
	 * @return \App\Models\CarRegisterTime  mixed
	 */
	public function unregister(bool $force = false)
	{
		try {
			$time = $this->getModel();
			$force ?
				$time->forceDelete() :
				$time->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully unregister car register time.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to unregister car register time.', $error);
		}

		return $this->returnResponse();
	}
}
