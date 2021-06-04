<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

class UploadCompanyLogoRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $company = $this->getCompany();

        return $user->hasCompanyPermission($company->id, 'edit companies');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_logo' => ['required', 'file', 'mimes:png,svg,jpeg,jpg', 'max:1024'],
        ]);

        return $this->returnRules();
    }
}
