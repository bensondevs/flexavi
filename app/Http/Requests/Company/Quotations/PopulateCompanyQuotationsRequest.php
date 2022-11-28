<?php

namespace App\Http\Requests\Company\Quotations;

use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyQuotationsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-appointment');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options(): array
    {
        if ($status = $this->input('status')) {
            $this->addWhere([
                'column' => 'status',
                'operator' => '=',
                'value' => $status,
            ]);
        }

        return $this->collectCompanyOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (strtobool($this->input('with_customer'))) {
            $this->addWith('customer');
        }
        
        if (strtobool($this->input('with_appointment'))) {
            $this->addWith('appointment');
        }
    }
}
