<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\{Builder, Model, Scope};

class CurrentQuarterScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $date = carbon('-3 months');
        $start = $date->startOfQuarter();
        $end = $date->endOfQuarter();

        $builder->where('created_at', '>=', $start)->where('created_at', '<=', $end);
    }
}
