<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CurrentQuarterScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder)
    {
        $date = carbon('-3 months');
        $start = $date->startOfQuarter();
        $end = $date->endOfQuarter();

        $builder->where('created_at', '>=', $start)->where('created_at', '<=', $end);
    }
}