<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Inspector;

class InspectorRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Inspector);
	}

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

	public function delete(bool $force = false)
	{
		try {
			$inspector = $this->getModel();
			$force ?
				$inspector->forceDelete() :
				$inspector->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete inspector.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete inspector.', $error);
		}

		return $this->returnResponse();
	}
}
