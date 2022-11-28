<?php

namespace App\Repositories\Analytics\Models;

use Illuminate\Support\Collection;

class ChartModel
{
    /**
     * Temporary stored data for calculation
     *
     * @var Collection
     */
    private $collection;

    /**
     * Set collection
     *
     * @param Collection  $collection
     * @return void
     */
    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Set collection using array
     *
     * @param array  $array
     * @return void
     */
    public function setArray(array $array)
    {
        $this->collection = collect($array);
    }

    /**
     * Save result to database
     *
     * @return void
     */
    public function saveAnalytic()
    {
        // TODO: complete saveAnalytic logic
    }
}
