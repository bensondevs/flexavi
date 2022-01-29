<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Inspection;

class InspectionRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Inspection);
	}

	/**
	 * Start inspection
	 * 
	 * @return 
	 */
	public function startInspection()
	{
		$question = Inspection::QUESTIONS[0][0];
		$inspection = new Inspection();
		return [
			// 'inspection_id' => ,

			'number' => 1,
			'phase' => 1,
			'question' => $question['question'],
			'choices' => $question['choices'],
			'options' => $question['options'],
		];
	}

	/**
	 * Handle inspection on answer next
	 * 
	 * @return void
	 */
	public function handleNext(array $answer)
	{
		$phase = $answer['phase'];
		$number = $answer['number'];
		$choice = isset($answer['choice']) ? $answer['choice'] : null;
		$options = isset($answer['options']) ? $answer['options'] : null;
		$data = isset($answer['data']) ? $answer['data'] : null;
		
		if ($phase == 1) {
			if ($number == 1) {
				$answer = $answer['answer'];

				if ($choice == 'yes') {
					// Upload the image
				} else if ($choice == 'no') {
					// 
				}
			}
		}

		if ($phase == 2) {
			// 
		}

		if ($phase == 3) {
			// 
		}

		if ($phase == 4) {
			// 
		}
	}

	/**
	 * Save inspection data
	 * 
	 * @param  array  $inspectionData
	 * @return \App\Models\Inspection
	 */
	public function save(array $inspectionData)
	{
		try {
			$inspection = $this->getModel();
			$inspection->fill($inspectionData);
			$inspection->save();

			$this->setModel($inspection);

			$this->setSuccess('Successfully save inspection data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save inspection data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete inspection
	 * 
	 * @param  bool  $force
	 */
	public function delete(bool $force = false)
	{
		try {
			$inspection = $this->getModel();
			$force ?
				$inspection->forceDelete() :
				$inspection->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete inspection.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError(
				'Failed to delete inspection.', 
				
			);
		}

		return $this->returnResponse();
	}
}
