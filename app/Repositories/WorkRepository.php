<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Work;

class WorkRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Work);
	}

	public function save(array $workData)
	{
		try {
			$work = $this->getModel();
			$work->fill($workData);
			$work->save();

			// Update quotation
			$quotation = $work->quotation;
            $quotation->countAmount();
            $quotation->save();

            if ($appointmentId = $quotation->appointment_id) {
            	$work->appointment_id = $appointmentId;
            	$work->save();
            }

            $this->setModel($work);

			$this->setSuccess('Successfully save work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create work.', $error);
		}

		return $this->getModel();
	}

	public function markUnfinished(string $unfinishNote = '')
	{
		try {
			$work = $this->getModel();
			$work->markUnfinished($unfinishNote);

			$this->setModel($work);

			$this->setSuccess('Successfully mark work as unfinished.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark work as unfinished.', $error);
		}

		return $this->getModel();
	}

	public function markFinsihed(string $finishNote = '')
	{
		try {
			$work = $this->getModel();
			$work->markFinsihed($finishNote);

			$this->setModel($work);

			$this->setSuccess('Successfully mark work as finished.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark work as finsihed.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$work = $this->getModel();
			$quotation = $work->quotation;
			$force ? 
				$work->forceDelete() : 
				$work->delete();

			// Update quotation
			$quotation->countAmount();
			$quotation->save();

			$this->destroyModel();

			$this->setSuccess('Successfully delete work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete work.', $error);
		}

		return $this->returnResponse();
	}
}
