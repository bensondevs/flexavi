<?php

namespace App\Traits;

trait ApiCollectionResource 
{
    public static function apiCollection($resource)
    {
        // Transform
        $resource->data = (self::collection($resource));
        
        // Convert array
        $array = $resource->toArray()['data'];
        $array = array_values($array);

        $resource->setCollection(collect($array));

        return $resource;
    }
}