<?php

namespace App\Traits;

trait Searchable 
{
    public static function getSearchable()
    {
        return isset(self::$searchable) ? self::$searchable : [];
    }

    public static function getSearchableColumns()
    {
        return self::getSearchable();
    }
}