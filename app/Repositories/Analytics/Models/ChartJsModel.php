<?php

namespace App\Repositories\Analytics\Models;

class ChartJsModel extends ChartModel
{
	/**
	 * Array of datasets
	 * 
	 * @var array
	 */
	public $chartDatasets;

	/**
	 * Array of chart labels
	 * 
	 * @var array
	 */
	public $chartLabels;

	/**
	 * Add label to the chart
	 * 
	 * @param string  $label
	 * @return void
	 */
	public function addLabel(string $label)
	{
		array_push($this->chartLabels, $label);
	}

	/**
	 * Set chart labels
	 * 
	 * @param array  $chartLabels
	 * @return void
	 */
	public function setChartLabels(array $chartLabels)
	{
		$this->chartLabels = $chartLabels;
	}

	/**
	 * Remove label from the chart
	 * 
	 * @param int|string  $target
	 * @return void
	 */
	public function removeLabel($target)
	{
		if (is_int($target)) {
			isset($this->chartLabels[$target]) ? 
				unset($this->chartLabels[$target]) : null;

			return true;
		}

		if (is_string($target)) {
			array_walk($this->chartLabels, function ($label, $key) {
				if ($label == $target) unset($this->chartLabels[$key]);
			});
		}

		return false;
	}

	/**
	 * Set chart labels for the chart
	 * 
	 * @param array  $labels
	 * @return void
	 */
	public function setLabels(array $labels)
	{
		$this->chartLabels = $labels;
	}

	/**
	 * Add dataset to collection data
	 * 
	 * @param string  $label
	 * @param array  $datasetData
	 * @param string|null  $borderColor
	 * @param string|null  $bgColor
	 * @return void
	 */
	public function addDataset(
		string $label, 
		array $datasetData, 
		string $borderColor = null, 
		string $bgColor = null
	) {
		array_push($this->chartData, [
			'label' => $label,
			'data' => $datasetData,
			'borderColor' => $borderColor,
			'backgroundColor' => $bgColor,
		]);
	}

	/**
	 * Generate chart for front-end
	 * 
	 * @return array
	 */
	public function generateChartData()
	{
		return $this->chartData;
	}
}