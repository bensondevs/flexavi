<?php

namespace App\Services\Algolia;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class AlgoliaService
{
    /**
     *  Search with algolia
     *
     * @param string $keyword
     * @param array $onModels
     * @return Collection|null
     */
    public static function search(string $keyword, array $onModels = []): ?Collection
    {
        $data = collect();

        $onModels = array_filter(
            array_map(
                function ($onModel) {
                    $onModel = $onModel instanceof Model ? get_class($onModel) : $onModel;
                    return $onModel && self::isSearchable($onModel) ? $onModel : null;
                },
                empty($onModels) ? [] : $onModels
            )
        );

        $searchableModels = !empty($onModels) ? $onModels : self::searchableModels();

        foreach ($searchableModels as $searchableModel) {
            //  continue the loop if doesn't have permission to search on specific model
            if (!self::canSearchOn($searchableModel)) {
                continue;
            }

            $model = new $searchableModel();
            $searchableFields = $model->getSearchableFields();
            // if there is no searchable fields on the model -> continue the loop
            if (empty($searchableFields)) {
                continue;
            }

            $pagination = $model->search($keyword)->query(function (Builder $query) use ($model) {
                return $query->with(
                    array_keys($model->getSearchableRelations())
                )->where("deleted_at", null); // dont search on deleted model
            })->paginate(10);

            foreach ($pagination as $p) {
                $data->push(
                    collect(
                        $p
                    )->merge([
                        "model" => $searchableModel
                    ])
                );
            }
        }

        return $data->groupBy(
            fn ($d) => strtolower(
                Str::plural(Str::afterLast($d['model'], "\\"))
            )
        )->unique();
    }

    /**
     *  Determine is Model Searchable
     *
     * @param string|Model|null $model
     * @return bool
     */
    public static function isSearchable(Model|string|null $model): bool
    {
        $model = $model instanceof Model ? get_class($model) : $model;
        return in_array(
            \App\Traits\Searchable::class,
            class_uses_recursive($model)
        );
    }

    /**
     *  Get Searchable Models
     *
     * @return array
     */
    public static function searchableModels(): array
    {
        $path = app_path("Models");
        $files = File::allFiles($path);

        $modelNames = array_map(function ($file) {
            $pathinfo = pathinfo($file) ;
            $dirname = $pathinfo["dirname"] ;
            $namespace = "App\\" . (Str::afterLast( // get the class namespace
                Str::replace("/", "\\", $dirname),
                'app\\'
            ));
            $className = Str::beforeLast($pathinfo["basename"], ".") ; // get filename without extension
            return "$namespace\\$className"; // returned model namespace + class name
        }, $files);

        $searchableModelNames = array_filter( // filter models and return the model that has "Searchable" trait
            $modelNames, // the model collection/list being map
            fn ($modelName) => self::isSearchable($modelName)
        );
        return $searchableModelNames ;
    }

    /**
     *  Determine the user has permission/can  search on model
     *
     * @param string|Model $model
     * @param null $forUser
     * @return bool
     */
    public static function canSearchOn(Model|string $model, $forUser = null): bool
    {
        if ($model instanceof Model) {
            $model = get_class($model);
        }

        $model = strtolower(Str::afterLast($model, "\\"));

        $gateName = "view-any-" . $model;

        return $forUser ?
            Gate::forUser($forUser)->allows($gateName) :
            Gate::allows($gateName);
    }
}
