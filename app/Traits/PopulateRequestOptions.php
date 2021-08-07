<?php

namespace App\Traits;

trait PopulateRequestOptions
{
	private $search = '';
	private $withs = [];
	private $withCounts = [];
	private $wheres = [];
	private $whereRaws = [];
	private $whereHases = [];

	public function addWith(string $relation)
	{
		array_push($this->withs, $relation);
	}

	public function setWiths(array $relations)
	{
		$this->withs = $relations;
	}

	public function addWithCount(string $countRelation)
	{
		array_push($this->withCounts, $countRelation);
	}

	public function setWithCounts(array $countRelations)
	{
		$this->withCounts = $countRelations;
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

	public function addWhereRaw(string $query)
	{
		$this->whereRaws[] = $query;
	}

	public function setWhereRaws(array $queries)
	{
		$this->whereRaws = $queries;
	}

	public function addWhereHas(string $relation, array $conditions = [])
	{
		$this->whereHases[$relation] = isset($this->whereHases[$relation]) ? 
			$this->whereHases[$relation] : [];

		foreach ($conditions as $condition) {
			$this->whereHases[$relation][] = [
				'column' => $condition['column'],
				'operator' => isset($condition['operator']) ?
					$condition['operator'] : '=',
				'value' => $condition['value'],
			];
		}

	}

	public function setWhereHases(array $queries)
	{
		$relation = $queries['relation'];
		$this->whereHases[$relation] = [];

		$conditions = isset($queries['conditions']) ? $queries['conditions'] : [];
		foreach ($conditions as $condition) {
			$this->whereHases[$relation][] = [
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
            'with_counts' => $this->withCounts,
            'wheres' => $this->wheres,
            'where_raws' => $this->where_raws,
            'where_hases' => $this->whereHases,
        ];

        return $options;
    }
}