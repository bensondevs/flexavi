<?php 

namespace App\Repositories\Base;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\QueryException;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Traits\RepositoryResponse;

class BaseRepository 
{
	use RepositoryResponse;

	private $defaultModel = null;
	private $model = null;
	private $resource;
	private $parentModel = null;
	private $collection = null;
	private $paginations = null;
	private $defaultPaginationPerPage = 10;

	public function setInitModel(Model $model)
	{
		$this->model = $model;
		$this->defaultModel = clone $model;
	}

	public function setModel($model)
	{
		return $this->model = $model;
	}

	public function getModel()
	{
		return $this->model;
	}

	public function destroyModel()
	{
		$this->model = null;
		$this->model = clone $this->defaultModel;
	}

	public function setParentModel(Model $parentModel)
	{
		return $this->parentModel = $parentModel;
	}

	public function getParentModel()
	{
		return $this->parentModel;
	}

	public function setCollection(Collection $collection)
	{
		return $this->collection = $collection;
	}

	public function getCollection()
	{
		return $this->collection;
	}

	public function destroyCollection()
	{
		$this->collection = collect();
	}

	public function setPagination(LengthAwarePaginator $paginations)
	{
		return $this->paginations = $paginations;
	}

	public function getPagination()
	{
		return $this->paginations;
	}

	public function destroyPagination()
	{
		return $this->paginations = null;
	}

	public function all(array $options = [], bool $pagination = false)
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

		if (isset($options['scopes'])) {
			if ($options['scopes']) {
				foreach ($options['scopes'] as $scope) {
					$models = $models->{$scope}();
				}
			}
		}

		if (isset($options['wheres'])) {
			if ($options['wheres']) {
				foreach ($options['wheres'] as $condition) {
					$operator = isset($condition['operator']) ? 
						$condition['operator'] : 
						'=';
					$clause = isset($condition['clause']) ?
						$condition['clause'] :
						'where';

					$models = $models->{$clause}(
						$condition['column'], 
						$operator, 
						$condition['value']
					);
				}
			}
		}

		if (isset($options['where_hases'])) {
			if ($options['where_hases']) {
				foreach ($options['where_hases'] as $relation => $conditions) {
					$models = $models->whereHas($relation, function ($query) use ($conditions) {
						foreach ($conditions as $condition) {
							$operator = isset($condition['operator']) ? 
								$condition['operator'] : 
								'=';
							$clause = isset($condition['clause']) ?
								$condition['clause'] :
								'where';
							
							$query->{$clause}(
								$condition['column'], 
								$operator, 
								$condition['value']
							);
						}
					});
				}
			}
		}

		if (isset($options['per_page'])) {
			if ($options['per_page']) {
				$this->defaultPaginationPerPage = $options['per_page'];
			}
		}

		if (isset($options['search'])) {
			if ($options['search']) {
				$searchableColumns = $this->getModel()->getSearchable();
				foreach ($searchableColumns as $key => $column) {
					$models = ($key == 0) ?
						$models->where($column, 'like', '%' . $options['search'] . '%') :
						$models->orWhere($column, 'like', '%' . $options['search'] . '%');
				}
			}
		}

		$models = $models->get();
		$this->setCollection($models);

		return ($pagination) ? $this->paginate() : $models;
	}

	public function trasheds(array $options = [], bool $pagination = false)
	{
		$model = $this->getModel();
		$model = $model->onlyTrashed();
		$this->setModel($model);

		return $this->all($options, $pagination);
	}

	public function paginate($perPage = null, $page = null, $options = [])
	{
		if (! $perPage) {
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

	public function find($id)
	{
		$model = $this->getModel();
		$model = $model->find($id);

		$this->setModel($model);

		return $this->getModel();
	}

	public function freshSearch(Model $model, $keyword)
	{
		$columns = $model->getFillable();

		foreach ($columns as $key => $column) {
			if ($key == 0)
				$model->where($column, 'like', '%' . $keyword . '%');
			else
				$model->orWhere($column, 'like', '%' . $keyword . '%');
		}

		$this->setCollection($model->get());

		return $this->getCollection();
	}

	public function search($keyword)
	{
		$collection = $this->getCollection() ?
			$this->getCollection() :
			$this->all();
	}
}