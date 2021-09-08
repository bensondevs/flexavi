<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Receipt;

class ReceiptRepository extends BaseRepository
{
	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Receipt);
	}

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

	public function replace($receiptable)
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

	public function attachTo($receiptable)
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

	public function delete(bool $force = false)
	{
		try {
			$receipt = $this->getModel();
			$force ?
				$receipt->forceDelete() :
				$receipt->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete receipt.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete receipt.', $error);
		}

		return $this->returnResponse();
	}

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
