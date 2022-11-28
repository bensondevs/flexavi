<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DefaultSetting
{
    /**
     * Scope a query for getting default setting of related module.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('company_id', null);
    }

     /**
     * Create callable "default" attribute
     * This callable attribute will return status enum description
     *
     * @return object
     */
    public function getDefaultAttribute()
    {
        return self::query()->default()->first();
    }
}
