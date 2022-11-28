<?php

namespace App\Rules;

use App\Models\Company\Company;
use Illuminate\Contracts\Validation\Rule;

class ExistInCompany implements Rule
{
    private $company;
    private $recordTable;
    private $companyColumn;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        Company $company,
        string $recordTable,
        string $companyColumn = 'company_id'
    )
    {
        $this->company = $company;
        $this->recordTable = $recordTable;
        $this->companyColumn = $companyColumn;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The company does not own this .';
    }
}
