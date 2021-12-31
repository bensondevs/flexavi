<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Setting;

class SettingRepository extends BaseRepository
{
	/**
	 * Company target of the setting
	 * 
	 * @var \App\Models\Company
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
}
