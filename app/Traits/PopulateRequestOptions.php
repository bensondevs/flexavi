<?php

namespace App\Traits;

trait PopulateRequestOptions
{
    /**
     * Populate request attributes
     *
     * @var string
     */
    private string $search = '';
    private string $searchScope = 'table_scope_only';
    private array $searchOnRelations = [];
    private array $withs = [];
    private array $withCounts = [];
    private bool $withTrashed = false;
    private array $wheres = [];
    private array $whereIns = [];
    private array $whereNotNulls = [];
    private array $whereJsonContainses = [];
    private array $whereRaws = [];
    private array $whereHases = [];
    private array $whereHasMorphs = [];
    private array $scopes = [];
    private array $joins = [];
    private array $orderBys = [];
    private array $withCountWheres = [];
    private array $whereHasWhereIns = [];
    private array $whereBetweens = [];
    private array $whereYears = [];

    /**
     * Add with relations
     *
     * @param string $relations
     * @return void
     */
    public function addWith(string $relations): void
    {
        $this->withs[] = $relations;
    }

    /**
     * Set with relations
     *
     * @param array $relations
     * @return void
     */
    public function setWiths(array $relations): void
    {
        $this->withs = $relations;
    }

    /**
     * Add a search on relations
     *
     * @param string $relations
     * @return void
     */
    public function addSearchOnRelation(string $relations): void
    {
        $this->searchOnRelations[] = $relations;
    }

    /**
     * Set a search on relations
     *
     * @param array $relations
     * @return void
     */
    public function setSearchOnRelations(array $relations): void
    {
        $this->searchOnRelations = $relations;
    }

    /**
     * Add query where json contain
     *
     * @param array $query
     * @return void
     */
    public function addWhereJsonContains(array $query): void
    {
        $this->whereJsonContainses[] = [
            'column' => $query['column'],
            'inside_column' => $query['inside_column'],
            'value' => $query['value'],
        ];
    }

    /**
     * Set query where json contain
     *
     * @param array $queries
     * @return void
     */
    public function setWhereJsonContainses(array $queries): void
    {
        foreach ($queries as $query) {
            $this->whereJsonContainses[] = [
                'column' => $query['column'],
                'inside_column' => $queries['inside_column'],
                'value' => $query['value'],
            ];
        }
    }

    /**
     * Add a count attribute
     *
     * @param string $countRelation
     * @return void
     */
    public function addWithCount(string $countRelation): void
    {
        $this->withCounts[] = $countRelation;
    }

    /**
     * Set with count attribute
     *
     * @param array $countRelations
     * @return void
     */
    public function setWithCounts(array $countRelations): void
    {
        $this->withCounts = $countRelations;
    }

    /**
     * Add query with count where
     *
     * @param array $withCountWhere
     * @return void
     */
    public function addWithCountWhere(array $withCountWhere): void
    {
        $this->withCountWheres[] = $withCountWhere;
    }

    /**
     * Set query with count where
     *
     * @param array $withCountWheres
     * @return void
     */
    public function setWithCountWheres(array $withCountWheres): void
    {
        $this->withCountWheres = $withCountWheres;
    }

    /**
     * Add join query
     *
     * @param array $join
     * @return void
     */
    public function addJoin(array $join): void
    {
        $this->join[] = [
            'relation' => $join['relation'],
            'relation_column' => $join['relation_column'],
            'operator' => $join['operator'] ?? '=',
            'model_column' => $join['model_column'],
        ];
    }

    /**
     * Add where query
     *
     * @param array $condition
     * @return void
     */
    public function addWhere(array $condition): void
    {
        $this->wheres[] = [
            'column' => $condition['column'],
            'operator' => $condition['operator'] ?? '=',
            'value' => $condition['value'],
        ];
    }

    /**
     * Add where year query
     *
     * @param array $condition
     * @return void
     */
    public function addWhereYear(array $condition): void
    {
        $this->whereYears[] = [
            'column' => $condition['column'],
            'value' => $condition['value'],
        ];
    }

    /**
     * Add where between query
     *
     * @param array $condition
     * @return void
     */
    public function addWhereBetween(array $condition): void
    {
        $this->whereBetweens[] = [
            'column' => $condition['column'],
            'value' => $condition['values'],
        ];
    }

    /**
     * Set where query
     *
     * @param array $conditions
     * @return void
     */
    public function setWhereBetweens(array $conditions): void
    {
        foreach ($conditions as $condition) {
            $this->whereBetweens[] = [
                'column' => $condition['column'],
                'value' => $condition['values'],
            ];
        }
    }

    /**
     * Set where query
     *
     * @param array $conditions
     * @return void
     */
    public function setWheres(array $conditions): void
    {
        foreach ($conditions as $condition) {
            $this->wheres[] = [
                'column' => $condition['column'],
                'operator' => $condition['operator'] ?? '=',
                'value' => $condition['value'],
            ];
        }
    }

    /**
     * Set where years query
     *
     * @param array $conditions
     * @return void
     */
    public function setWhereYears(array $conditions): void
    {
        foreach ($conditions as $condition) {
            $this->whereYears[] = [
                'column' => $condition['column'],
                'value' => $condition['value'],
            ];
        }
    }

    /**
     * Add where in query
     *
     * @param array $condition
     * @return void
     */
    public function addWhereIn(array $condition): void
    {
        $this->whereIns[] = [
            'column' => $condition['column'],
            'values' => $condition['values'],
        ];
    }

    /**
     * Set where in query
     *
     * @param array $conditions
     * @return void
     */
    public function setWhereIns(array $conditions): void
    {
        foreach ($conditions as $condition) {
            $this->whereIns[] = [
                'column' => $condition['column'],
                'values' => $condition['values'],
            ];
        }
    }

    /**
     * Add where not null query
     *
     * @param string $column
     * @return void
     */
    public function addWhereNotNull(string $column): void
    {
        $this->whereNotNulls[] = $column;
    }

    /**
     * Add where raw query
     *
     * @param string $query
     * @return void
     */
    public function addWhereRaw(string $query): void
    {
        $this->whereRaws[] = $query;
    }

    /**
     * Set where raw query
     *
     * @param array $queries
     * @return void
     */
    public function setWhereRaws(array $queries): void
    {
        $this->whereRaws = $queries;
    }

    /**
     * Add where has query
     *
     * @param string $relation
     * @param array $conditions
     * @return void
     */
    public function addWhereHas(string $relation, array $conditions = []): void
    {
        $this->whereHases[$relation] = $this->whereHases[$relation] ?? [];

        foreach ($conditions as $condition) {
            $this->whereHases[$relation][] = [
                'column' => $condition['column'],
                'operator' => $condition['operator'] ?? '=',
                'value' => $condition['value'],
            ];
        }
    }

    /**
     * Add where has query
     *
     * @param string $relation
     * @param array $conditions
     * @return void
     */
    public function addWhereHasWhereIn(string $relation, array $conditions = []): void
    {
        $this->whereHasWhereIns[$relation] = $this->whereHasWhereIns[$relation] ?? [];

        foreach ($conditions as $condition) {
            $this->whereHasWhereIns[$relation][] = [
                'column' => $condition['column'],
                'values' => $condition['values'],
            ];
        }
    }

    /**
     * Set where hases query
     *
     * @param array $queries
     * @return void
     */
    public function setWhereHases(array $queries): void
    {
        $relation = $queries['relation'];
        $this->whereHases[$relation] = [];

        $conditions = $queries['conditions'] ?? [];
        foreach ($conditions as $condition) {
            $this->whereHases[$relation][] = [
                'column' => $condition['column'],
                'operator' => $condition['operator'] ?? '=',
                'value' => $condition['value'],
            ];
        }
    }

    /**
     * Set where has where ins query
     *
     * @param array $queries
     * @return void
     */
    public function setWhereHasWhereIns(array $queries): void
    {
        $relation = $queries['relation'];
        $this->whereHases[$relation] = [];

        $conditions = $queries['conditions'] ?? [];
        foreach ($conditions as $condition) {
            $this->whereHases[$relation][] = [
                'column' => $condition['column'],
                'values' => $condition['values'],
            ];
        }
    }

    /**
     * Add where has morphable query
     *
     * @param string $relation
     * @param array $morphClasses
     * @param array $conditions
     * @return void
     */
    public function addWhereHasMorph(
        string $relation,
        array  $morphClasses,
        array  $conditions = []
    ): void
    {
        $this->whereHasMorphs[$relation] = [
            'classes' => $morphClasses,
            'conditions' => $conditions,
        ];
    }

    /**
     * Set where has morphable query
     *
     * @param array $whereHasMorphs
     * @return void
     */
    public function setWhereHasMorphs(array $whereHasMorphs = []): void
    {
        $this->whereHasMorphs = $whereHasMorphs;
    }

    /**
     * Add order by query
     *
     * @param [type] $column
     * @param string $type
     * @return void
     */
    public function addOrderBy($column, string $type = 'DESC'): void
    {
        $this->orderBys[] = [
            'column' => $column,
            'type' => $type,
        ];
    }

    /**
     * Set order by query
     *
     * @param array $orderBys
     * @return void
     */
    public function setOrderBys(array $orderBys): void
    {
        $this->orderBys = $orderBys;
    }

    /**
     * Add a scope
     *
     * @param string $name
     * @param [type] $parameters
     * @return void
     */
    public function addScope(string $name, $parameters): void
    {
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }
        if (isset($this->scopes[$name])) {
            $scope = is_array($this->scopes[$name]) ? $this->scopes[$name] : [];
            $parameters = array_merge($scope, $parameters);
        }
        $this->scopes[$name] = $parameters;
    }

    /**
     * Collect the options
     *
     * @return array
     */
    public function collectOptions(): array
    {
        if ($search = $this->input('search')) {
            $this->setSearch($search);
        }
        if ($searchScope = $this->input('search_by')) {
            $this->setSearchScope($searchScope);
        }
        if ($withTrashed = $this->input('with_trashed')) {
            $withTrashed = strtobool($withTrashed);
            $this->withTrashed($withTrashed);
        }
        $perPage = is_numeric($this->input('per_page'))
            ? $this->input('per_page')
            : 10;
        return [
            'per_page' => $perPage,
            'search' => $this->search,
            'search_scope' => $this->searchScope,
            'withs' => $this->withs,
            'with_counts' => $this->withCounts,
            'with_count_wheres' => $this->withCountWheres,
            'with_trashed' => $this->withTrashed,
            'wheres' => $this->wheres,
            'where_ins' => $this->whereIns,
            'where_not_nulls' => $this->whereNotNulls,
            'where_raws' => $this->where_raws,
            'where_hases' => $this->whereHases,
            'where_has_where_ins' => $this->whereHasWhereIns,
            'where_has_morphs' => $this->whereHasMorphs,
            'order_bys' => $this->orderBys,
            'where_json_containses' => $this->whereJsonContainses,
            'where_betweens' => $this->whereBetweens,
            'where_years' => $this->whereYears,
        ];
    }

    /**
     * Set search query
     *
     * @param string $search
     * @return void
     */
    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    /**
     * Set search scope
     *
     * @param string $scope
     * @return void
     */
    public function setSearchScope(string $scope): void
    {
        $this->searchScope = $scope;
    }

    /**
     * Add with trashed data
     *
     * @param boolean $setting
     * @return void
     */
    public function withTrashed(bool $setting = false): void
    {
        $this->withTrashed = $setting;
    }
}
