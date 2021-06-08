<?php

namespace App\Traits;

trait PopulateRequestOptions
{
	private $search = '';
	private $withs = [];
	private $wheres = [];
	private $whereHases = [];

	public function addWith(string $relation)
	{
		array_push($this->withs, $relation);
	}

	public function setWiths(array $relations)
	{
		$this->withs = $relations;
	}

	public function addWhere(array $condition)
	{
		array_push($this->wheres, [
			'column' => $condition['column'],
			'operator' => isset($condition['operator']) ?
				$condition['operator'] : '=',
			'value' => $condition['value'],
		]);
	}

	public function setWheres(array $conditions)
	{
		foreach ($conditions as $condition) {
			array_push($this->wheres, [
				'column' => $condition['column'],
				'operator' => isset($condition['operator']) ?
					$condition['operator'] : '=',
				'value' => $condition['value'],
			]);
		}
	}

	public function addWhereHas(string $relation, array $condition)
	{
		$this->whereHases[$relation] = [
			'column' => $condition['column'],
			'operator' => isset($condition['operator']) ?
				$condition['operator'] : '=',
			'value' => $condition['value'],
		];
	}

	public function setWhereHases(array $conditions)
	{
		foreach ($conditions as $condition) {
			$this->whereHases[$condition['relation']] = [
				'column' => $condition['column'],
				'operator' => isset($condition['operator']) ?
					$condition['operator'] : '=',
				'value' => $condition['value'],
			];
		}
	}

	public function setSearch(string $search)
	{
		$this->search = $search;
	}

    public function collectOptions()
    {
    	if ($search = $this->input('search'))
    		$this->setSearch($search);

    	$perPage = is_numeric($this->input('per_page')) ?
    		$this->input('per_page') : 10;
        $options = [
        	'per_page' => $perPage,
        	'search' => $this->search,
            'withs' => $this->withs,
            'wheres' => $this->wheres,
            'where_hases' => $this->whereHases,
        ];

        return $options;
    }
}