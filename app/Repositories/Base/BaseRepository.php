<?php 

namespace App\Repositories\Base;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\RepositoryPayload;

class BaseRepository 
{
	use RepositoryPayload;

	protected $defaultModel = null;
	protected $model = null;
	protected $collection = null;

	public function setInitModel(Model $model)
	{
		$this->model = $model;
		$this->defaultModel = $model;
	}

	public function setModel(Model $model)
	{
		$this->model = $model;
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

	public function all(array $options = [])
	{
		$models = $this->getModel();

		if (isset($options['withs']))
			$models->with($options['withs']);

		if (isset($options['wheres']))
			foreach ($options['wheres'] as $column => $value)
				$models->where($column, $value);

		if (isset($options['where_likes']))
			foreach ($options['wheres'] as $column => $value)
				$models->where($column, 'like', $value);
		
		return $models->get();
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