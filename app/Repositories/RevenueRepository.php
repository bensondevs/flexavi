<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Work;
use App\Models\Revenue;

class RevenueRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Revenue);
	}

	public function save(array $revenueData = [])
	{
		try {
			$revenue = $this->getModel();
			$revenue->fill($revenueData);
			$revenue->save();

			$this->setModel($revenue);

			$this->setSuccess('Successfully save revenue.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save revenue.', $error);
		}

		return $this->getModel();
	}

	public function recordWork(Work $work, array $revenueData = [])
	{
		try {
			$revenueData['company_id'] = $work->company_id;
			$revenueData['revenue_name'] = isset($revenueData['revenue_name']) ? 
				$revenueData['revenue_name'] : 
				$work->description;
			$revenueData['amount'] = $work->total_price;
			$revenueData['paid_amount'] = isset($revenueData['paid_amount']) ? 
				$revenueData['paid_amount'] : 0;

			$revenue = $this->save($revenueData);
			$work->attachRevenue($revenue);

			$this->setSuccess('Successfully record revenue.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to record revenue.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$revenue = $this->getModel();
			$force ?
				$revenue->forceDelete() :
				$revenue->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete revenue.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete revenue.', $error);
		}

		return $this->returnResponse();
	}
}
