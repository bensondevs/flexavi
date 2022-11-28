<?php

namespace App\Http\Requests\Company\Mandates;

use App\Models\Company\MollieCompanyMandate;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class FindMandateRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * MollieCompanyMandate object
     *
     * @var MollieCompanyMandate|null
     */
    private $companyMandate;

    /**
     * Get MollieCompanyMandate based on supplied input
     *
     * @return void
     */
    public function getCompanyMandate()
    {
        if ($this->companyMandate) {
            return $this->companyMandate;
        }
        $id = $this->input('id') ?: $this->input('company_mandate_id');

        return $this->companyMandate = MollieCompanyMandate::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
