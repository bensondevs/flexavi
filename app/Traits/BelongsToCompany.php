<?php

namespace App\Traits;

use App\Models\Company;

trait BelongsToCompany 
{
    /**
     * Create relationship with company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check current model that instelled with this trait
     * is belongs to certain company.
     * 
     * @param \App\Models\Company  $company
     * @return bool
     */
    public function isBelongsToCompany(Company $company)
    {
        //
    }
}