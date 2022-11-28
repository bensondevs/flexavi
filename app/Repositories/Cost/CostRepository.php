<?php

namespace App\Repositories\Cost;

use App\Jobs\Cost\DeleteAttachlessCosts;
use App\Models\Cost\Cost;
use App\Repositories\Base\BaseRepository;
use Exception;
use Illuminate\Database\QueryException;

class CostRepository extends BaseRepository
{
    /**
     * Repository class constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Cost());
    }

    /**
     * Save cost
     *
     * @param array  $costData
     * @return Cost|null
     */
    public function save(array $costData = [])
    {
        try {
            $cost = $this->getModel();
            $cost->fill($costData);
            $cost->save();
            $this->setModel($cost);
            $this->setSuccess('Successfully save cost.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save cost.', $error);
        }

        return $this->getModel();
    }

    /**
     * Record cost to a costable model
     *
     * @param mixed  $costable
     * @return Cost|null
     */
    public function record($costable)
    {
        $costableType = get_lower_class($costable);
        try {
            $costableTypePlural = str_to_plural($costableType);
            $cost = $this->getModel();
            $cost
                ->{$costableTypePlural}()
                ->attach($costable, ['company_id' => $costable->company_id]);
            $cost->save();
            $this->setModel($cost);
            $this->setSuccess(
                'Successfully record cost in ' . $costableType . '.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to record cost in ' . $costableType . '.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Record many cost to costable
     *
     * @param mixed $costable
     * @param array $costs
     * @return bool
     */
    public function recordMany($costable, array $costs)
    {
        $costableType = get_lower_class($costable);
        try {
            $pivotData = array_fill(0, count($costs), [
                'company_id' => $costable->company_id,
            ]);
            $costs = array_combine($costs, $pivotData);
            $costable->costs()->syncWithoutDetaching($costs);
            $this->setSuccess(
                'Successfully record many costs in ' . $costableType . '.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to record many costs in ' . $costableType . '.',
                $error
            );
        }

        return $this->returnResponse();
    }

    /**
     * Replace costables with new list of costs
     *
     * @param mixed $costable
     * @param array $costs
     * @return bool
     */
    public function replaceRecord($costable, array $costs)
    {
        $costableType = get_lower_class($costable);
        try {
            $costable->costs()->syncWithoutDetaching($costs);
            $this->setSuccess(
                'Successfully replace recorded costs in ' . $costableType . '.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to replace recorded costs in ' . $costableType . '.',
                $error
            );
        }

        return $this->returnResponse();
    }

    /**
     * Unrecord cost from costable
     *
     * @param mixed $costable
     * @return bool
     */
    public function unrecord($costable)
    {
        $costableType = get_lower_class($costable);
        try {
            $costableTypePlural = str_to_plural($costableType);
            $cost = $this->getModel();
            $cost->{$costableTypePlural}()->detach($costable);
            if ($cost->costables()->count() < 1) {
                $cost->delete();
            }
            $this->setSuccess(
                'Successfully unrecord cost in ' . (string) $costableType . '.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to unrecord cost in ' . (string) $costableType . '.',
                $error
            );
        }

        return $this->returnResponse();
    }

    /**
     * Unrecord many costs from costable model
     *
     * @param mixed $costable
     * @param array $costIds
     * @return bool
     */
    public function unrecordMany($costable, array $costIds)
    {
        $costableType = get_lower_class($costable);
        try {
            $costable->costs()->detach($costIds);
            $this->setSuccess(
                'Successfully unrecord many costs in ' . $costableType . '.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to unrecord many costs in ' . $costableType . '.',
                $error
            );
        }

        return $this->returnResponse();
    }

    /**
     * Truncate costs from costable model
     *
     * @param mixed $costable
     * @return bool
     */
    public function truncate($costable)
    {
        $costableType = get_lower_class($costable);
        try {
            $costs = $costable->costs;
            $costIds = [];
            foreach ($costs as $cost) {
                $costIds[] = $cost->id;
            }
            $costable->costs()->detach();
            $deleteAttachlessCost = new DeleteAttachlessCosts($costIds);
            $deleteAttachlessCost->delay(1);
            dispatch($deleteAttachlessCost);
            $this->setSuccess(
                'Successfully unrecord all costs within ' . $costableType
            );
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed to unrecord costs within ' . $costableType);
        }

        return $this->returnResponse();
    }

    /**
     * Delete cost, set parameter to true to execute force delete
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $cost = $this->getModel();
            $force ? $cost->forceDelete() : $cost->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete cost.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete cost.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore cost from soft-deleted state
     *
     * @return Cost|null
     */
    public function restore()
    {
        try {
            $cost = $this->getModel();
            $cost->restore();
            $this->setModel($cost);
            $this->setSuccess('Successfully restore cost.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore cost.', $error);
        }

        return $this->getModel();
    }
}
