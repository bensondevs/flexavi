<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyAppointmentsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $company = $this->getCompany();
        return $this->checkCompanyPermission('view appointments', $company, 'id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function options()
    {
        return $this->collectCompanyOptions();
    }
}
