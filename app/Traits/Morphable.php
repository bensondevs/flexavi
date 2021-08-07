<?php

namespace App\Traits;

trait Morphable 
{
    private $morphTypeColumn = '';
    private $morphIdColumn = '';

    public static function findMorph($type, $id)
    {
        $table = self::$table;

        if (! $typeColumn = $morphTypeColumn) {
            $typeColumn = str_to_singular($table) . '_type';
        }

        if (! $idColumn = $morphIdColumn) {
            $idColumn = str_to_singular($table) . '_id';
        }

        return self::where($typeColumn, $type)->where($idColumn, $id)->first();
    }

    public static function findMorphOrFail($type, $id)
    {
        if (! $morph = $this->findMorph($type, $id)) {
            return abort(404, 'Failed to find morph value.');
        }

        return $morph;
    }
}