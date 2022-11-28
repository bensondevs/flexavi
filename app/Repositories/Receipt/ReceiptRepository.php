<?php

namespace App\Repositories\Receipt;

use App\Models\Receipt\Receipt;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ReceiptRepository extends BaseRepository
{
    /**
     * Create New Repository Instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Receipt());
    }

    /**
     * Save or update receipt
     *
     * @param array $receiptData
     * @return Receipt|null
     */
    public function save(array $receiptData = [])
    {
        try {
            $receipt = $this->getModel();
            $receipt->fill($receiptData);
            if (isset($receiptData['receipt_image'])) {
                $receipt->receipt_image = $receiptData['receipt_image'];
            }
            $receipt->save();
            $this->setModel($receipt);
            $this->setSuccess('Successfully save receipt.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save receipt.', $error);
        }

        return $this->getModel();
    }

    /**
     * Replace the receipt of a receiptable
     *
     * @param Model $receiptable
     * @return Receipt|null
     */
    public function replace(Model $receiptable)
    {
        try {
            DB::beginTransaction();
            $oldReceipt = $receiptable->receipt;
            $oldReceipt->delete();
            $receipt = $this->getModel();
            $receipt->receiptable_type = get_class($receiptable);
            $receipt->receiptable_id = $receiptable->id;
            $receipt->save();
            DB::commit();
            $this->setModel($receipt);
            $this->setSuccess('Successfully replace receipt.');
        } catch (QueryException $qe) {
            DB::rollBack();
            $error = $qe->getMessage();
            $this->setError('Failed to replace receipt.', $error);
        }

        return $this->getModel();
    }

    /**
     * Attach receipt to receiptable
     *
     * @param Model $receiptable
     * @return Receipt|null
     */
    public function attachTo(Model $receiptable)
    {
        try {
            $receipt = $this->getModel();
            $receipt->receiptable_type = get_class($receiptable);
            $receipt->receiptable_id = $receiptable->id;
            $receipt->save();
            $this->setModel($receipt);
            $this->setSuccess('Successfully attach receipt.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to attach receipt.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete a receipt
     *
     * @param boolean $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $receipt = $this->getModel();
            $force ? $receipt->forceDelete() : $receipt->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete receipt.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete receipt.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore a trashed receipt
     *
     * @return Receipt|null
     */
    public function restore()
    {
        try {
            $receipt = $this->getModel();
            $receipt->restore();
            $this->setModel($receipt);
            $this->setSuccess('Successfully restore receipt.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore receipt.', $error);
        }

        return $this->getModel();
    }
}
