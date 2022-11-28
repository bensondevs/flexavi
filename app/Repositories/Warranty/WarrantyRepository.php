<?php

namespace App\Repositories\Warranty;

use App\Models\Warranty\Warranty;
use App\Models\Warranty\WarrantyWork;
use App\Models\Work\Work;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WarrantyRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Warranty());
    }

    /**
     * Save warranty
     *
     * @param  array  $warrantyData
     * @return Warranty|null
     */
    public function save(array $warrantyData)
    {
        try {
            $warranty = $this->getModel();
            $warranty->fill($warrantyData);
            $warranty->save();
            $this->setModel($warranty);
            $this->setSuccess('Successfully save warranty.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save warranty.', $error);
        }

        return $this->getModel();
    }

    /**
     * Select works to be attached to warranty
     *
     * @param  array  $workIds
     * @return Warranty|null
     */
    public function selectWorks(array $workIds)
    {
        // TODO: complete selectWorks logic
        // try {
        // } catch (QueryException $qe) {
        //     $error = $qe->getMessage();
        //     $message = 'Failed to select works to be attached to warranty.';
        //     $this->setError($message, $error);
        // }

        return $this->getModel();
    }

    /**
     * Attach a work to the warranty
     *
     * @param  Work  $work
     * @param  array $extras
     * @return Warranty|null
     */
    public function attachWork(Work $work, array $extras = [])
    {
        try {
            $warranty = $this->getModel();
            WarrantyWork::create(
                array_merge(
                    [
                        'warranty_id' => $warranty->id,
                        'work_id' => $work->id,
                    ],
                    $extras
                )
            );
            $this->setSuccess('Successfully attach work to warranty.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to attach work to warranty.', $error);
        }

        return $this->getModel();
    }

    /**
     * Set status for warranty
     *
     * @param  int  $status
     * @param  bool $applyToAllWorks
     * @return Warranty|null
     */
    public function setStatus(int $status, $applyToAllWorks = false)
    {
        try {
            $warranty = $this->getModel();
            $warranty->status = $status;
            $warranty->save();
            if ($applyToAllWorks) {
                $warranty->warrantyWorks()->update([
                    'status' => $status,
                ]);
            }
            $this->setModel($warranty);
            $this->setSuccess('Successfully set status for warranty.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to set status for warranty.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete warranty
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $warranty = $this->getModel();
            $force ? $warranty->forceDelete() : $warranty->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete warranty.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete warranty.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore soft-deleted warranty
     *
     * @return Warranty|null
     */
    public function restore()
    {
        try {
            $warranty = $this->getModel();
            $warranty->restore();
            $this->setModel($warranty);
            $this->setSuccess('Successfully restore warranty');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore warranty', $error);
        }

        return $this->getModel();
    }
}
