<?php

namespace App\Traits;

trait PopulateRequestOptions
{
	private $search = '';
	private $withs = [];
	private $withCounts = [];
	private $withTrashed = false;
	private $wheres = [];
	private $whereNotNulls = [];
	private $whereRaws = [];
	private $whereHases = [];
	private $whereHasMorphs = [];
	private $scopes = [];
	private $joins = [];
	private $orderBys = [];

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

	public function withTrashed(bool $setting = false)
	{
		$this->withTrashed = $setting;
	}

	public function addJoin(array $join)
	{
		$this->join[] = [
			'relation' => $join['relation'],
			'relation_column' => $join['relation_column'],
			'operator' => isset($join['operator']) ? $join['operator'] : '=',
			'model_column' => $join['model_column'],
		];
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

	public function addWhereNotNull(string $column)
	{
		$this->whereNotNulls[] = $column;
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

	public function addWhereHasMorph(string $relation, array $morphClasses, array $conditions = [])
	{
		$this->whereHasMorphs[$relation] = [
			'classes' => $morphClasses,
			'conditions' => $conditions,
		];
	}

	public function setWhereHasMorphs(array $whereHasMorphs = [])
	{
		$this->whereHasMorphs = $whereHasMorphs;
	}

	public function setSearch(string $search)
	{
		$this->search = $search;
	}

	public function addOrderBy($column, $type = 'DESC')
	{
		$this->orderBys[] = [
			'column' => $column,
			'type' => $type,
		];
	}

	public function setOrderBys(array $orderBys)
	{
		$this->orderBys = $orderBys;
	}

	public function addScope(string $name, $parameters)
	{
		if (! is_array($parameters)) {
			$parameters = [$parameters];
		}

		if (isset($this->scopes[$name])) {
			$scope = is_array($this->scopes[$name]) ? $this->scopes[$name] : [];
			$parameters = array_merge($scope, $parameters);
		}

		$this->scopes[$name] = $parameters;
	}

    public function collectOptions()
    {
    	if ($search = $this->input('search')) {
    		$this->setSearch($search);
    	}

    	if ($withTrashed = $this->input('with_trashed')) {
            $withTrashed = strtobool($withTrashed);
            $this->withTrashed($withTrashed);
        }

    	$perPage = is_numeric($this->input('per_page')) ?
    		$this->input('per_page') : 10;
        $options = [
        	'per_page' => $perPage,
        	'search' => $this->search,
            'withs' => $this->withs,
            'with_counts' => $this->withCounts,
            'with_trashed' => $this->withTrashed,
            'wheres' => $this->wheres,
            'where_not_nulls' => $this->whereNotNulls,
            'where_raws' => $this->where_raws,
            'where_hases' => $this->whereHases,
            'where_has_morphs' => $this->whereHasMorphs,
            'order_bys' => $this->orderBys,
        ];

        return $options;
    }
}