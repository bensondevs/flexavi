<?php

namespace App\Traits;

use App\Models\Company\Company;

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
     * @param  Company $company
     * @return bool
     */
    public function isBelongsToCompany(Company $company)
    {
        return $this->attributes['company_id'] == $company->id;
    }
}
