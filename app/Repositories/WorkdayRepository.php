<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Workday;
use App\Models\Company;

class WorkdayRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Workday);
	}

	public function generateWorkdays()
	{
		$start = now()->startOfMonth();
		$end = now()->endOfMonth()->addDay();

		foreach (Company::all() as $company) {
			$rawWorkdays = [];
			for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
				$rawWorkdays[] = [
					'id' => generateUuid(),
					'company_id' => $company->id,
					'date' => $date->copy(),
					'created_at' => now(),
					'updated_at' => now(),
				];
			}
			Workday::insert($rawWorkdays);
		}
	}

	public function current(Company $company)
	{
		return Workday::where('company_id', $company->id)
			->where('date', now()->toDateString())
			->first();
	}

	public function process()
	{
		try {
			$workday = $this->getModel();
			$workday->process();

			$this->setModel($workday);

			$this->setSuccess('Successfully process workday.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to process workday.', $error);
		}

		return $this->getModel();
	}

	public function calculate()
	{
		try {
			$workday = $this->getModel();
			$workday->calculate();

			$this->setModel($workday);

			$this->setSuccess('Successfully calculate workday.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to calculate workday.', $error);
		}

		return $this->getModel();
	}
}
