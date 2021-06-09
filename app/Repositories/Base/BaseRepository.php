<?php 

namespace App\Repositories\Base;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\QueryException;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Repositories\Base\RepositoryPayload;

class BaseRepository 
{
	use RepositoryPayload;

	protected $defaultModel = null;
	protected $model = null;
	protected $resource;
	protected $parentModel = null;
	protected $collection = null;
	protected $paginations = null;

	public function setInitModel(Model $model)
	{
		$this->model = $model;
		$this->defaultModel = $model;
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
		$this->model = $this->defaultModel;
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

		if (isset($options['search'])) {
			if ($options['search']) {
				$columns = $models->getFillable();
				foreach ($columns as $key => $column) {
					$models = ($key == 0) ?
						$models->where($column, 'like', '%' . $options['search'] . '%') :
						$models->orWhere($column, 'like', '%' . $options['search'] . '%');
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

		if (isset($options['withs']))
			if ($options['withs'])
				foreach ($options['withs'] as $relation)
					$models = $models->with($relation);

		$models = $models->get();
		$this->setCollection($models);

		return $models;
	}

	public function trasheds(array $options = [], bool $pagination = false)
	{
		$model = $this->getModel();
		$model = $model->onlyTrashed();
		$this->setModel($model);

		return $this->all($options, $pagination);
	}

	public function paginate($perPage = 10, $page = null, $options = [])
	{
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