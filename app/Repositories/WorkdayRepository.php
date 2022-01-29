<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Workday, Company };

class WorkdayRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Workday);
	}

	/**
	 * Generate workdays between range
	 * 
	 * @param  \DateTime|null  $start
	 * @param  \DateTime|null  $end
	 * @return bool
	 */
	public function generateWorkdays($start = null, $end = null)
	{
		try {
			$start = $start ?: now()->startOfMonth();
			$end = $end ?: now()->endOfMonth()->addDay();

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
			
			$this->setSuccess('Successfully generate workdays');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to generate workdays');
		}
	}

	/**
	 * Get current workday
	 * 
	 * @param  \App\Models\Company
	 * @return \App\Models\Workday|null
	 */
	public function current(Company $company)
	{
		return Workday::where('company_id', $company->id)
			->where('date', now()->toDateString())
			->first();
	}

	/**
	 * Process workday and set it's status to processed
	 * 
	 * @return \App\Models\Workday
	 */
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

	/**
	 * Set status of workday to be calculated
	 * 
	 * @return \App\Models\Workday
	 */
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
