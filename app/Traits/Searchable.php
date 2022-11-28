<?php

namespace App\Traits;

use Laravel\Scout\Searchable as ScoutSearchable;

trait Searchable
{
    use ScoutSearchable;

    /**
     * Get searchable fields
     *
     * @return array
     */
    public function getSearchableFields(): array
    {
        return $this->searchableFields;
    }

    /**
     * Get searchable relations
     *
     * @return array
     */
    public function getSearchableRelations(): array
    {
        if (isset($this->searchableRelations)) {
            return $this->searchableRelations;
        }

        return [];
    }
}
