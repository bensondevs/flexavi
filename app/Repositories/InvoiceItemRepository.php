<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\InvoiceItem;

class InvoiceItemRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new InvoiceItem);
	}

	public function save(array $itemData = [])
	{
		try {
			$item = $this->getModel();
			$item->fill($itemData);
			$item->save();

			$this->setModel($item);

			$this->setSuccess('Successfully add item to invoice');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add item to invoice.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$item = $this->getModel();
			$force ? 
				$item->forceDelete() :
				$item->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully remove item from invoice');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to remove item from invoice', $error);
		}

		return $this->getModel();
	}
}
