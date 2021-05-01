<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\JsonArray;

use App\Models\Company;

class RegisterCompanyRequest extends FormRequest
{
    protected $company;

    public function getCompany()
    {
        return new Company([
            'owner_id' => request()->user()
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasCompanyPermission($this->company);
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

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
