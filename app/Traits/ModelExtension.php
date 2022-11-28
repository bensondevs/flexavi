<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasManyThrough, HasOne, HasOneThrough};
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

trait ModelExtension
{
    /**
     * Find only soft-deleted model record in database.
     *
     * @static
     *
     * @param mixed $id
     * @return self
     */
    public static function findInTrash($id): static
    {
        return self::onlyTrashed()->find($id);
    }

    /**
     * Find only soft-deleted model record in database.
     * If record is not found, then throw 404 error exception.
     *
     * @static
     *
     * @param mixed $id
     * @return self
     */
    public static function findInTrashOrFail($id): static
    {
        return self::onlyTrashed()->findOrFail($id);
    }

    /**
     * Find model record including deleted records in database.
     *
     * @static
     *
     * @param mixed $id
     * @return self
     */
    public static function findIncludeTrash($id): static
    {
        return self::withTrashed()->find($id);
    }

    /**
     * Find model record including deleted records in database.
     * If record is not found, then throw 404 error exception.
     *
     * @static
     *
     * @param mixed $id
     * @return self
     */
    public static function findIncludeTrashOrFail($id): static
    {
        return self::withTrashed()->findOrFail($id);
    }

    /**
     * Find model records based on the array of ids.
     *
     * @static
     *
     * @param array $ids
     * @return Collection
     */
    public static function findAllIn(array $ids): Collection
    {
        return self::whereIn('id', $ids)->get();
    }

    /**
     * Insert big data using chunk and model insert
     *
     * @static
     *
     * @param Collection|array $bigData
     * @param int $chunkSize
     * @return bool
     */
    public static function insertBigData($bigData, int $chunkSize = 1000): bool
    {
        if (!$bigData instanceof Collection) {
            $bigData = collect($bigData);
        }
        if (count($bigData) <= $chunkSize) {
            $modelClass = get_called_class();
            return $modelClass::insert($bigData->toArray());
        }
        DB::beginTransaction();
        try {
            $modelClass = get_called_class();
            foreach ($bigData->chunk($chunkSize) as $dataChunk) {
                if (!$modelClass::insert($dataChunk->toArray())) {
                    return false;
                }
            }
            DB::commit();

            return true;
        } catch (QueryException $qe) {
            DB::rollback();
            error_log($qe->getMessage(), 0);

            return false;
        }
    }

    /**
     * Update record by specifying id and data.
     *
     * @static
     * @param int $id
     * @param array $updateData
     * @param bool $withTrashed
     * @return bool
     */
    public static function updateToRecord(
        int   $id,
        array $updateData,
        bool  $withTrashed = false
    ): bool
    {
        return ($withTrashed
            ? self::withTrashed()->where('id', $id)
            : self::where('id', $id)
        )->update($updateData);
    }

    /**
     * Massive update records in database based on collection
     * of array of ID supplied in first argument.
     *
     * @static
     * @param array $ids
     * @param array $updateData
     * @return bool
     */
    public static function massUpdate(array $ids, array $updateData): bool
    {
        return self::whereIn('id', $ids)->update($updateData);
    }

    /**
     * Find record in database if found and marked
     * as deleted, then directly restore it.
     *
     * @param int|string $id
     * @return self
     */
    public static function findOrRestore($id): static
    {
        $record = self::withTrashed($id)->find($id);
        if (!is_null($record->deleted_at)) {
            $record->restore();
        }

        return $record;
    }

    /**
     * Create callable method of `whereJson(string $jsonColumn, string $key, $value)`
     * This callable method will query to specified column that contains json
     * add do query into it's JSON structure
     *
     * @param Builder $query
     * @param string $jsonColumn
     * @param string $key
     * @param mixed $value
     * @return Builder
     */
    public function scopeWhereJson(
        Builder $query,
        string  $jsonColumn,
        string  $key,
        mixed   $value
    ): Builder
    {
        switch (true) {
            case is_array($value):
                $value = json_encode($value);
                break;

            case is_bool($value):
                $value = strtobool($value);
                break;

            case is_string($value):
                // Just let it be
                break;

            default:
                $value = (string)$value;
                break;
        }
        $queryStr =
            'JSON_CONTAINS(' . $jsonColumn . '->' . $key . ', ' . $value . ')';

        return $query->whereRaw($queryStr);
    }

    /**
     * Create callable method of `excludeIds(array $ids)`
     * This callable method will add query to exclude
     * model within specified $ids
     *
     * @param Builder $query
     * @param array $ids
     * @return Builder
     */
    public function scopeExcludeIds(Builder $query, array $ids): Builder
    {
        if (count($ids) < 1) {
            return $query;
        }

        return $query->whereNotIn('id', $ids);
    }

    /**
     * Create callable method of `groupByDuplicate`
     * This callable method will add query to only show
     * duplicated record based on certain column
     *
     * @param Builder $query
     * @param string|array $column
     * @param int $duplicate
     * @return Builder
     */
    public function scopeGroupByDuplicate(
        Builder $query,
                $column,
        int     $duplicate = 1
    ): Builder
    {
        return $query
            ->select('*', DB::raw('COUNT(*) as `duplicate_count`'))
            ->whereNotNull($column)
            ->groupBy($column);
    }

    /**
     * Check that a relationship is loaded and is not null
     *
     * @param string $relation
     * @return bool
     */
    public function relationFound(string $relation): bool
    {
        if (!$this->relationLoaded($relation)) {
            return false;
        }

        return isset($this->{$relation});
    }

    /**
     * Check that model attribute is dirty and
     * changed to certain value
     *
     * @param string $attribute
     * @param null $value
     * @return bool
     */
    public function isChangedTo(string $attribute, $value = null): bool
    {
        if (!$this->isDirty($attribute)) {
            return false;
        }

        return !isset($value) || $this->attributes[$attribute] === $value;
    }

    /**
     * Create new record and get all data
     * right after the creation.
     *
     * @param array $data
     * @return Collection
     */
    public function createAndGet(array $data): Collection
    {
        self::create($data);

        return self::all();
    }

    /**
     * Add pre-loaded relations to model by merging
     * array values to $with array.
     *
     * @param string|array $relations
     * @return $this
     */
    public function addWiths(...$relations): static
    {
        $this->with[] = $relations;

        return $this;
    }

    /**
     * Ensure model relationship is loaded successfully.
     *
     * @param string $relationName
     * @return $this
     */
    public function ensureRelationLoaded(string $relationName): static
    {
        if (!$this->relationLoaded($relationName)) {
            $this->load($relationName);
        }
        if ($this->{$relationName}->empty()) {
            $this->{$relationName} = match (true) {
                $this->{$relationName}() instanceof HasOne or
                $this->{$relationName}() instanceof HasOneThrough or
                $this->{$relationName}() instanceof BelongsTo => $this->{$relationName}->first(),
                $this->{$relationName}() instanceof HasMany or
                $this->{$relationName}() instanceof HasManyThrough or
                $this->{$relationName}() instanceof BelongsToMany => $this->{$relationName}->get(),
                default => $this->{$relationName}()->get(),
            };
        }

        return $this;
    }
}
