<?php

namespace App\Repositories\Base;

use App\Traits\RepositoryResponse;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\{LengthAwarePaginator, Paginator};
use Illuminate\Support\Collection;

class BaseRepository
{
    use RepositoryResponse;

    /**
     * Repository Default Model
     *
     * @var Model|null
     */
    private $defaultModel;

    /**
     * Repository Model
     *
     * @var Model|null
     */
    private $model;

    /**
     * Repository Parent Model
     *
     * @var Model|null
     */
    private $parentModel;

    /**
     * Repository Collection
     *
     * @var Collection|null
     */
    private $collection;

    /**
     * Repository paginations
     *
     * @var LengthAwarePaginator|array|null
     */
    private $paginations;

    /**
     * Repository Default Pagination Per Page
     *
     * @var int
     */
    private $defaultPaginationPerPage = 10;

    /**
     * Repository resource class
     *
     * @var mixed
     */
    private $resourceClass;

    /**
     * Set init model or default model
     *
     * @param Model $model
     * @return void
     */
    public function setInitModel(Model $model): void
    {
        $this->model = $model;
        $this->defaultModel = clone $model;
    }

    /**
     * Set repository model success
     *
     * @param Model $model
     * @param string $message
     * @return void
     */
    public function setModelSuccess($model, string $message = ''): void
    {
        $this->setModel($model);
        $this->setSuccess($message);
    }

    /**
     * Destroy repository model
     *
     * @return void
     */
    public function destroyModel(): void
    {
        $this->model = null;
        if (!is_null($this->defaultModel)) {
            $this->model = clone $this->defaultModel;
        }
    }

    /**
     * Get repository parent model
     *
     * @return Model|null
     */
    public function getParentModel(): ?Model
    {
        return $this->parentModel;
    }

    /**
     * Set repository parent model
     *
     * @param Model $parentModel
     * @return Model
     */
    public function setParentModel(Model $parentModel): Model
    {
        return $this->parentModel = $parentModel;
    }

    /**
     * Destroy repository collection
     *
     * @return void
     */

    public function destroyCollection(): void
    {
        $this->collection = collect();
    }

    /**
     * Get repository pagination
     *
     * @return LengthAwarePaginator|array|null
     */
    public function getPagination(): LengthAwarePaginator|array|null
    {
        return $this->paginations;
    }

    /**
     * Destroy repository pagination
     *
     * @return null
     */
    public function destroyPagination()
    {
        return $this->paginations = null;
    }

    /**
     * Set repository resource
     *
     * @param mixed $resourceClass
     * @return mixed
     */
    public function setResource(mixed $resourceClass): mixed
    {
        return $this->resourceClass = $resourceClass;
    }

    /**
     * Get repository resource
     *
     * @return mixed
     */
    public function getResource(): mixed
    {
        return $this->resourceClass;
    }

    /**
     * Get all data on trash according to parsed options
     *
     * @param array $options
     * @param bool $pagination
     * @return mixed
     */
    public function trasheds(array $options = [], bool $pagination = false): mixed
    {
        $model = $this->getModel();
        $model = $model->onlyTrashed();
        $this->setModel($model);

        return $this->all($options, $pagination);
    }

    /**
     * Get repository model
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set repository model
     *
     * @param mixed $model
     * @return mixed
     */
    public function setModel($model)
    {
        return $this->model = $model;
    }

    /**
     * Get all the data on the model according to the parsed options
     *
     * @param array $options
     * @param bool $pagination
     * @param bool $skipGet
     * @return mixed
     */
    public function all(
        array $options = [],
        bool  $pagination = false,
        bool  $skipGet = false
    ): mixed
    {
        $models = $this->getModel();
        if (isset($options['withs'])) {
            if ($options['withs']) {
                $models = $models->with($options['withs']);
            }
        }
        if (isset($options['with_counts'])) {
            if ($options['with_counts']) {
                $models = $models->withCount($options['with_counts']);
            }
        }
        if (isset($options['with_trashed'])) {
            if ($options['with_trashed'] === true) {
                $models = $models->withTrashed();
            }
        }
        if (isset($options['scopes'])) {
            if ($options['scopes']) {
                foreach ($options['scopes'] as $scope => $parameters) {
                    $models = $models->{$scope}(...$parameters);
                }
            }
        }
        if (isset($options['wheres'])) {
            foreach ($options['wheres'] as $condition) {
                $operator = $condition['operator'] ?? '=';
                $clause = $condition['clause'] ?? 'where';

                $models = $models->{$clause}(
                    $condition['column'],
                    $operator,
                    $condition['value']
                );
            }
        }

        if (isset($options['where_ins'])) {
            foreach ($options['where_ins'] as $condition) {
                $models = $models->whereIn(
                    $condition['column'],
                    $condition['values']
                );
            }
        }

        if (isset($options['where_not_nulls'])) {
            foreach ($options['where_not_nulls'] as $column) {
                $models = $models->whereNotNull($column);
            }
        }
        if (isset($options['where_raws'])) {
            foreach ($options['where_raws'] as $query) {
                $models = $models->whereRaw($query);
            }
        }

        if (isset($options['where_betweens'])) {
            foreach ($options['where_betweens'] as $condition) {
                $models = $models->whereBetween(
                    $condition['column'],
                    $condition['values']
                );
            }
        }
        if (isset($options['where_years'])) {
            foreach ($options['where_years'] as $condition) {
                $models = $models->whereYear(
                    $condition['column'],
                    $condition['values']
                );
            }
        }

        if (isset($options['where_hases'])) {
            foreach ($options['where_hases'] as $relation => $conditions) {
                $models = $models->whereHas($relation, function ($query) use ($conditions) {
                    foreach ($conditions as $condition) {
                        $operator = $condition['operator'] ?? '=';
                        $clause = $condition['clause'] ?? 'where';

                        $query->{$clause}(
                            $condition['column'],
                            $operator,
                            $condition['value']
                        );
                    }
                });
            }
        }

        if (isset($options['where_has_where_ins'])) {
            foreach ($options['where_has_where_ins'] as $relation => $conditions) {
                $models = $models->whereHas($relation, function ($query) use ($conditions) {
                    foreach ($conditions as $condition) {
                        $query->whereIn(
                            $condition['column'],
                            $condition['values']
                        );
                    }
                });
            }
        }

        if (isset($options['where_has_morphs'])) {
            foreach ($options['where_has_morphs'] as $relation => $morph) {
                $morphClasses = $morph['classes'];
                $morphConditions = $morph['conditions'];
                $models = $models->whereHasMorph(
                    $relation,
                    $morphClasses,
                    function (Builder $query) use ($morphConditions) {
                        foreach ($morphConditions as $condition) {
                            $operator = $condition['operator'] ?? '=';
                            $clause = $condition['clause'] ?? 'where';

                            $query->{$clause}(
                                $condition['column'],
                                $operator,
                                $condition['value']
                            );
                        }
                    }
                );
            }
        }
        if (isset($options['search'])) {
            if ($options['search']) {
                if ($options['search_scope'] == 'table_scope_only') {
                    $searchableColumns = $models->getModel()->getSearchableFields();
                    $searchableRelations = $models->getModel()->getSearchableRelations();
                    $models = $models->where(function ($query) use ($options, $searchableColumns, $searchableRelations) {
                        foreach ($searchableColumns as $key => $column) {
                            $key == 0 ? $query->where($column, 'like', '%' . $options['search'] . '%')
                                : $query->orWhere($column, 'like', '%' . $options['search'] . '%');
                        }

                        foreach ($searchableRelations as $relation => $relativeColumns) {
                            $query->orWhereHas($relation, function ($relationQuery) use ($options, $relativeColumns) {
                                foreach ($relativeColumns as $key => $column) {
                                    $relationQuery = $key == 0 ? $relationQuery->where($column, 'like', '%' . $options['search'] . '%')
                                        : $relationQuery->orWhere($column, 'like', '%' . $options['search'] . '%');
                                }
                            });
                        }
                    });
                }
            }
        }
        if (isset($options['order_bys'])) {
            foreach ($options['order_bys'] as $orderBy) {
                $column = $orderBy['column'];
                $orderType = $orderBy['type'] ?? 'DESC';

                $models = $models->orderBy($column, $orderType);
            }
        }
        if (isset($options['per_page'])) {
            $this->defaultPaginationPerPage = $options['per_page'];
        }
        if (!$skipGet) {
            $models = $models->get();
        }
        $this->setCollection($models);

        return $pagination ? $this->paginate() : $models;
    }

    /**
     * Set paginate on collection
     *
     * @param int|null $perPage
     * @param int|null $page
     * @param array $options
     * @return LengthAwarePaginator|array
     */
    public function paginate(int $perPage = null, int $page = null, array $options = []): LengthAwarePaginator|array
    {
        if (!$perPage) {
            $perPage = $this->defaultPaginationPerPage;
        }
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $this->getCollection();
        $paginations = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );

        return $this->setPagination($paginations);
    }

    /**
     * Get repository collection
     *
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * Set repository collection
     *
     * @param Collection $collection
     * @return Collection $collection
     */
    public function setCollection(Collection $collection): Collection
    {
        return $this->collection = $collection;
    }

    /**
     * Set repository pagination
     *
     * @param LengthAwarePaginator|array|Collection $paginations
     * @return LengthAwarePaginator|array|Collection
     */
    public function setPagination(LengthAwarePaginator|array|Collection $paginations): LengthAwarePaginator|array|Collection
    {
        return $this->paginations = $paginations;
    }

    /**
     * Set paginate by date (not by page)
     *
     * @param string $dateColumn
     * @param int|null $page
     * @param array|Closure $addsOnQuery
     * @return array
     */
    public function datePaginate(string $dateColumn = "created_at", int $page = null, array|Closure $addsOnQuery = []): array
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $this->getCollection();

        $options = is_array($addsOnQuery) ? $addsOnQuery : null;
        $callback = is_callable($addsOnQuery) ? $addsOnQuery : null;

        $companyID = isset($items->first()->company_id) ? $items->first()->company_id : $items->first()["company_id"] ?? null;

        $model = $this->getModel() instanceof Builder ? $this->getModel()->getModel() : $this->getModel();

        $pageLeft = $model->newQuery()
            ->select([
                \DB::raw("COUNT(*) OVER () AS count")
            ])
            ->where(
            // Column
                \DB::raw("DATE({$dateColumn})"),
                // Operator
                strtoupper(
                    is_array($options) && isset($options["order_by"]) ? $options["order_by"] : "DESC"
                ) == "DESC" ?
                    "<" : ">",
                // Condition / Value
                fn($query) => $query->select(\DB::raw("DATE($dateColumn) as {$dateColumn}"))
                    ->from($model->getTable())
                    ->when($companyID, fn($q) => $q->where("company_id", $companyID))
                    ->when(is_array($options), function ($query) use ($dateColumn, $options) {
                        $query = $query->orderBy($dateColumn, $options["order_by"] ?? "DESC");
                        return $query;
                    })
                    ->when(is_callable($callback), function ($query) use ($callback) {
                        return $callback($query);
                    })
                    ->distinct($dateColumn)
                    ->offset($page - 1)
                    ->limit(1)
            )
            ->when($companyID, fn($q) => $q->where("company_id", $companyID))
            ->when(is_callable($callback), function ($query) use ($callback) {
                return $callback($query);
            })
            ->groupBy(
                \DB::raw("DATE_FORMAT({$dateColumn}, '%Y-%m-%d')")
            )->first();
        $pageLeft = $pageLeft ? $pageLeft->count : 0;

        $paginations = array_merge([
            "current_page" => $page,
            "data" => $items,
            "per_page" => $this->defaultPaginationPerPage,
            "current_page_url" => "/?page={$page}",
            "first_page_url" => "/?page=1",
            "prev_page_url" => $page > 1 ? "/?page=" . ($page - 1) : null,
            "next_page_url" => $pageLeft ? "/?page=" . ($page + 1) : null,
            "last_page" => $pageLeft ? ($pageLeft + $page) : null,
            "last_page_url" => $pageLeft ? "/?page=" . ($pageLeft + $page) : null,
        ]);

        $this->setPagination($paginations);
        return $paginations;
    }

    /**
     * Find data by id on repository model
     *
     * @param mixed $id
     * @return Model|null
     */
    public function find(mixed $id): ?Model
    {
        $model = $this->getModel();
        $model = $model->find($id);
        $this->setModel($model);

        return $this->getModel();
    }

    /**
     * Fresh search on repository model
     *
     * @param Model $model
     * @param string $keyword
     * @return Collection $collection
     */
    public function freshSearch(Model $model, string $keyword): Collection
    {
        $columns = $model->getFillable();
        foreach ($columns as $key => $column) {
            if ($key == 0) {
                $model->where($column, 'like', '%' . $keyword . '%');
            } else {
                $model->orWhere($column, 'like', '%' . $keyword . '%');
            }
        }
        $this->setCollection($model->get());

        return $this->getCollection();
    }

    /**
     * Set search on repository model
     *
     * @param string $keyword
     * @return mixed
     */
    public function search(string $keyword): mixed
    {
        return $this->getCollection()->isNotEmpty()
            ? $this->getCollection()
            : $this->all();
    }
}
