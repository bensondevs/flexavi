<?php

namespace App\Repositories\Customer;

use App\Models\Customer\CustomerNote;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class CustomerNoteRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new CustomerNote());
    }

    /**
     * Save to create or update customer note
     *
     * @param array $noteData
     * @return CustomerNote|null
     */
    public function save(array $noteData): ?CustomerNote
    {
        try {
            $customerNote = $this->getModel();
            $customerNote->fill($noteData);
            $customerNote->save();
            $this->setModel($customerNote);
            $this->setSuccess('Successfully save customer note.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save customer note.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete customer note
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $customerNote = $this->getModel();
            $force ? $customerNote->forceDelete() : $customerNote->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete customer note.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete customer note.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore customer
     *
     * @return CustomerNote|null
     */
    public function restore(): ?CustomerNote
    {
        try {
            $customerNote = $this->getModel();
            $customerNote->restore();
            $this->setModel($customerNote);
            $this->setSuccess('Successfully restore customer note.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore customer note.', $error);
        }

        return $this->getModel();
    }


}
