<?php

namespace App\Rules;

use App\Models\Company\Company;
use Illuminate\Contracts\Validation\Rule;

class CompanyRecordOwned implements Rule
{
    /**
     * Company model container
     *
     * @var \App\Models\Company\Company
     */
    private $company;

    /**
     * Checked model container
     *
     * @var mixed
     */
    private $model;

    /**
     * Company ID column
     */
    private $companyIdColumn;

    private $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @param  \App\Models\Company\Company  $company
     * @param  mixed  $model
     * @param  string  $companyIdColumn
     * @return void
     */
    public function __construct(Company $company, $model, string $companyIdColumn = 'company_id')
    {
        $this->company = $company;
        $this->model = $model;
        $this->companyIdColumn = $companyIdColumn;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $id)
    {
        if (! $record = $this->model->find($id)) {
            $this->errorMessage = 'Failed to find record.';
            return false;
        }

        if ($record->{$this->companyIdColumn} !== $this->company->id) {
            $this->errorMessage = 'This record does not belong to the company.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
