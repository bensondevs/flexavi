<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Company;

class PopulateCompanyAppointmentsRequest extends FormRequest
{
    public $company;

    public function getCompany()
    {
        return $this->company ?:
            Company::findOrFail(
                request()->input('company_id')
            );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasCompanyPermission(
            $this->getCompany()->id
        );
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
}
