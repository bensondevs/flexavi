<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

class SaveCompanyRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('owner');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'visiting_addresss' => ['required', 'string', new JsonArray],
            'invoiving_address' => ['required', 'string', new JsonArray],
            'email' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'vat_number' => ['required', 'string'],
            'commerce_chamber_number' => ['required', 'string'],
            'company_logo' => ['required', 'file', 'mimes:jpg,jpeg,png,svg'],
            'company_website_url' => ['required', 'string'],
        ];
    }
}
