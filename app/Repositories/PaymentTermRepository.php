<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\PaymentTerm;

class PaymentTermRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new PaymentTerm);
	}

	public function save(array $termData)
	{
		try {
			$term = $this->getModel();
			$term->fill($termData);
			$term->save();

			$this->setModel($term);

			$this->setSuccess('Successfully save payment term.');
		} catch (QueryException $qe) {
			$this->setError('Failed to save payment term.');
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$term = $this->getModel();
			$force ? 
				$term->forceDelete() : 
				$term->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete payment term.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete payment term.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
