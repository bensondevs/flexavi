<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Work;

class WorkRepository extends BaseRepository
{
	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Work);
	}

	/**
	 * Save work model to database
	 * 
	 * @param  array  $workData
	 * @return \App\Models\Work
	 */
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

	/**
	 * Attach work model to any work attachable model 
	 * 
	 * @param   mixed  $workable
	 * @return  \App\Models\Work
	 */
	public function attachTo($workable)
	{
		try {
			$work = $this->getModel();
			$work->{get_plural_lower_class($workable)}()->attach($workable);

			$this->setModel($work);

			$this->setSuccess('Successfully attach work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to attach work.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Attach many works instance to any work attachable
	 * 
	 * @param  mixed  $workable
	 * @param  bool
	 */
	public function attachToMany($workable, array $works)
	{
		try {
			$workable->works()->attach($works);

			$this->setSuccess('Successfully attach many works.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to attach many works.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Detach work instance from any work attachable
	 * 
	 * @param  mixed  $workable
	 * @return  \App\Models\Work
	 */
	public function detachFrom($workable)
	{
		try {
			$work = $this->getModel();
			$workable->works()->detach($work);

			$this->setModelSuccess($work, 'Successfully detach work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to detach work.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Detach many work instances from any work attachable
	 * 
	 * @param  mixed  $workable
	 * @param  array  $works
	 */
	public function detachFromMany($workable, $works)
	{
		try {
			$workable->works()->detach($works);
			$this->setSuccess('Successfully detach many works.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to detach many works.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Truncate work instances attached to any work attachable 
	 * 
	 * @param  mixed  $workable
	 */
	public function truncate($workable)
	{
		try {
			$workable->works()->detach();
			$this->setSuccess('Successfully truncate works.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to truncate works.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Set work status to "Unfinished"
	 * 
	 * @param  string|null  $unfinishNote
	 * @return \App\Models\Work
	 */
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

	/**
	 * Set work status to "Finished"
	 * 
	 * @param  string|null  $finishNote
	 * @return \App\Models\Work
	 */
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

	/**
	 * Delete record of the model set in repository
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$work = $this->getModel();
			$quotation = $work->quotation;
			$force ? $work->forceDelete() : $work->delete();

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
