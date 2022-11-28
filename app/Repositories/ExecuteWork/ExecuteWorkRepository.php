<?php

namespace App\Repositories\ExecuteWork;

use App\Models\ExecuteWork\ExecuteWork;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class ExecuteWorkRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new ExecuteWork());
    }

    /**
     * Save executeWork data
     *
     * @param  array  $executeWorkData
     * @return ExecuteWork|null
     */
    public function save(array $executeWorkData)
    {
        try {
            $executeWork = $this->getModel();
            $executeWork->fill($executeWorkData);
            $executeWork->save();

            $this->setModel($executeWork);
            $this->setSuccess('Successfully create execute work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to create execute work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Execute work and create execute-work model
     *
     * @param  array  $executionData
     * @return ExecuteWork|null
     */
    public function execute(array $executionData = [])
    {
        try {
            $execute = $this->getModel();
            $execute->fill($executionData);
            $execute->save();
            $this->setModel($execute);
            $this->setSuccess('Successfully execute work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to execute work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Finish execute work
     *
     * @param  array  $finishData
     * @return ExecuteWork|null
     */
    public function finish(array $finishData = [])
    {
        try {
            $execute = $this->getModel();
            $execute->finish($finishData);
            $this->setModel($execute);
            $this->setSuccess('Successfully finish execute work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to finish execute work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete execute work
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $execute = $this->getModel();
            $force ? $execute->forceDelete() : $execute->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete execute work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete execute work.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore execute work
     *
     * @return ExecuteWork|null
     */
    public function restore()
    {
        try {
            $execute = $this->getModel();
            $execute->restore();
            $this->setModel($execute);
            $this->setSuccess('Successfully restore execute work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore execute work.', $error);
        }

        return $this->getModel();
    }
}
