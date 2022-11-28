<?php

namespace App\Traits;

trait ApiCollectionResource
{
    public static function apiCollection($resource)
    {
        $resource->data = self::collection($resource);
        $array = $resource->toArray()['data'];
        $array = array_values($array);
        $resource->setCollection(collect($array));

        return $resource;
    }
}
