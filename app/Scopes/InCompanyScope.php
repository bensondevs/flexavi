<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\{ Builder, Model, Scope };

class InCompanyScope implements Scope
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
        if (auth()->check()) {
            $company = auth()->user()->company;
            $builder->where('company_id', $company->id);
        }
    }
}