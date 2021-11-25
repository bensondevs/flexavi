<?php

namespace App\Repositories\Analytics\Models;

use Illuminate\Support\Collection;
use App\Models\Analytic;

class ChartModel
{
	/**
	 * Temporary stored data for calculation
	 * 
	 * @var Illuminate\Support\Collection
	 */
	private $collection;

	/**
	 * Set collection
	 * 
	 * @param Illuminate\Support\Collection  $collection
	 * @return void
	 */
	public function setCollection(Collection $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * Set collection using array
	 * 
	 * @param array  $array
	 * @return void
	 */
	public function setArray(array $array)
	{
		$this->collection = collect($array);
	}

	/**
	 * Save result to database
	 * 
	 * @return \App\Models\Analytic  $analytic
	 */
	public function saveAnalytic()
	{
		//
	}
}