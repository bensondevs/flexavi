<?php

namespace App\Repositories\Inspection;

use App\Models\Inspection\Inspector;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class InspectorRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Inspector());
    }

    /**
     * Save inspector
     *
     * @param  array  $inspectorData
     * @return Inspector|null
     */
    public function save(array $inspectorData = [])
    {
        try {
            $inspector = $this->getModel();
            $inspector->fill($inspectorData);
            $inspector->save();
            $this->setModel($inspector);
            $this->setSuccess('Successfully save inspector.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save inspector.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete inspector
     *
     * @param  bool  $force
     * @return Inspector|null
     */
    public function delete(bool $force = false)
    {
        try {
            $inspector = $this->getModel();
            $force ? $inspector->forceDelete() : $inspector->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete inspector.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete inspector.', $error);
        }

        return $this->returnResponse();
    }
}
