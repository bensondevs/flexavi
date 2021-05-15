<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Invoice;
use App\Models\InvoiceItem;

use App\Repositories\Base\BaseRepository;

class InvoiceRepository extends BaseRepository
{
	private $item;

	public function __construct()
	{
		$this->setInitModel(new Invoice);
		$this->item = new InvoiceItem;
	}

	public function save(array $invoiceData)
	{
		try {
			$invoice = $this->getModel();
			$invoice->fill($invoiceData);
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully save invoice data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save invoice data.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force)
	{
		try {
			$invoice = $this->getModel();
			$force ?
				$invoice->forceDelete() :
				$invoice->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete invoice.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete invoice', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}

	public function setItem(InvoiceItem $item)
	{
		$this->item = $item;
	}

	public function findItem($itemId)
	{
		return $this->item = $this->item->find($itemId);
	}

	public function addItem(array $itemData)
	{
		try {
			$invoice = $this->getModel();
			$invoice->items()->save($itemData);

			$this->setModel($invoice);

			$this->setSuccess('Successfully add item to invoice');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to add item to invoice', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function saveItem(array $itemData)
	{
		try {
			$item = $this->item;
			$item->fill($itemData);
			$item->save();

			$this->setItem($item);

			$this->setSuccess('Successfully save item data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save item data.',
				$qe->getMessage()
			);
		}

		return $this->item;
	}

	public function deleteItem(bool $force = false)
	{
		try {
			$force ?
				$this->item->forceDelete() :
				$this->item->delete();

			$this->setSuccess('Successfully delete invoice item.');
		} catch (QueryException $qe) {
			$this->setError('Failed to delete invoice item.');
		}

		return $this->item;
	}
}
