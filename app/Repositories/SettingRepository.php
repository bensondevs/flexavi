<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Setting, SettingValue, Company };

class SettingRepository extends BaseRepository
{
	/**
	 * Company target of the setting
	 * 
	 * @var \App\Models\Company|null
	 */
	private $company;

	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Setting);
	}

	/**
	 * Set company for this class process
	 * 
	 * @param  \App\Models\Company  $company
	 * @return void
	 */
	public function setCompany(Company $company)
	{
		$this->company = $company;
	}

	/**
	 * Get company for this class process
	 * 
	 * @return \App\Models\Company|null
	 */
	public function getCompany()
	{
		return $this->company;
	}

	/**
	 * Check if current repository class has company
	 * 
	 * @return  bool
	 */
	public function hasCompany()
	{
		return (bool) $this->company;
	}

	/**
	 * Set setting value. If company value is specified,
	 * then this method will set the company setting value
	 * 
	 * @param  mixed  $value
	 * @return bool
	 */
	public function setValue($value)
	{
		try {
			$setting = $this->getModel();
			if ($this->hasCompany()) {
				$company = $this->getCompany();
				$setting->setCompanyValue($value, $company);
			} else {
				$setting->setDefaultValue($value);
			}
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set setting value.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Reset setting to default value
	 * 
	 * @param  int  $type
	 * @return bool
	 */
	public function resetDefault(int $type)
	{
		try {
			if (! $this->hasCompany()) {
				return abort(500, 'No target company to be reset to default.');
			}

			SettingValue::forCompany($this->getCompany())
				->whereHas('setting', function ($query) use ($type) {
					$query->where('type', $type);
				})->delete();

			$this->setSuccess('Successfully reset settings as default.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to reset default company settings.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Reset all settings to default value
	 * 
	 * @return bool
	 */
	public function resetAllDefault()
	{
		try {
			if (! $this->hasCompany()) {
				return abort(500, 'No target company to be reset to default.');
			}

			SettingValue::forCompany($this->getCompany())->delete();

			$this->setSuccess('Successfully reset all setting as default.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to reset all setting to default.', $error);
		}

		return $this->returnResponse();
	}
}
