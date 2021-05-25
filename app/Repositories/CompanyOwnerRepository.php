<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Owner;

use App\Repositories\Base\BaseRepository;

class CompanyOwnerRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Owner);
	}

	public function save(array $ownerData)
	{
		try {
			$owner = $this->getModel();
			$owner->fill($ownerData);
			$owner->save();

			$this->setModel($owner);

			$this->setSuccess('Successfully create owner.');
		} catch (QueryException $qe) {
			$this->setError('Failed to create owner.');
		}

		return $this->getModel();
	}
}
