<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Inspection;

use App\Repositories\Base\BaseRepository;

class InspectionRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Inspection);
	}

	public function startInspection()
	{
		$question = Inspection::QUESTIONS[0][0];
		$inspection = new Inspection();
		return [
			'inspection_id' => ,

			'number' => 1,
			'phase' => 1,
			'question' => $question['question'],
			'choices' => $question['choices'],
			'options' => $question['options'],
		];
	}

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

	public function save(array $inspectionData)
	{
		try {
			$inspection = $this->getModel();
			$inspection->fill($inspectionData);
			$inspection->save();

			$this->setModel($inspection);

			$this->setSuccess('Successfully save inspection data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save inspection data.',
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force)
	{
		try {
			$inspection = $this->getModel();
			$force ?
				$inspection->forceDelete() :
				$inspection->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete inspection.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete inspection.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
