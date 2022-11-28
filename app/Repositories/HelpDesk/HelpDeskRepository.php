<?php

namespace App\Repositories\HelpDesk;

use App\Models\HelpDesk\HelpDesk;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class HelpDeskRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new HelpDesk());
    }

    /**
     * Save help desk
     *
     * @param  array  $data
     * @return HelpDesk|null
     */
    public function save(array $data)
    {
        try {
            $helpDesk = $this->getModel();
            $helpDesk->fill($data);
            $helpDesk->save();
            $this->setModel($helpDesk);
            $this->setSuccess('Successfully save help desk.');
        } catch (QueryException $th) {
            $error = $th->getMessage();
            $this->setError('Failed to store help desk. ' . $error);
        }

        return $this->getModel();
    }

    /**
     * Delete model
     *
     * @return bool
     */
    public function delete()
    {
        try {
            $helpDesk = $this->getModel();
            $helpDesk->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete help desk.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete help desk', $error);
        }

        return $this->returnResponse();
    }
}
