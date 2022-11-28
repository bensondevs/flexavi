<?php

namespace App\Http\Requests\Company\Companies;

use App\Models\Company\Company;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $user = $this->user();
        $company =
            request()->get('_company') ?: $user->{$user->user_role}->company;
        if (!$company) {
            $this->company = new Company();
            return $user->fresh()->hasPermissionTo('register companies');
        }
        $this->company = $this->model = $company;

        return $user->fresh()->hasPermissionTo('edit companies');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company = $this->getCompany();
        $this->setRules([
            'company_name' => ['required', 'string'],
            'email' => [
                'required',
                'string',
                Rule::unique('companies', 'email')->ignore($company->id)
            ],
            'phone_number' => [
                'required',
                'string',
                Rule::unique('companies', 'phone_number')->ignore($company->id)
            ],
            'vat_number' => [
                'nullable',
                'string',
                Rule::unique('companies', 'vat_number')->ignore($company->id)
            ],
            'commerce_chamber_number' => ['nullable', 'numeric'],
            'company_website_url' => ['nullable', 'string'],

            // Visiting Address
            'visiting_address' => ['nullable', 'string'],
            'visiting_address_house_number' => ['nullable', 'numeric'],
            'visiting_address_house_number_suffix' => ['string'],
            'visiting_address_zipcode' => ['nullable', 'numeric'],
            'visiting_address_city' => ['nullable', 'string'],
            'visiting_address_province' => ['nullable', 'string'],

            // Invoicing Address
            'invoicing_address' => ['nullable', 'string'],
            'invoicing_address_house_number' => ['nullable', 'numeric'],
            'invoicing_address_house_number_suffix' => ['string'],
            'invoicing_address_zipcode' => ['nullable', 'numeric'],
            'invoicing_address_city' => ['nullable', 'string'],
            'invoicing_address_province' => ['nullable', 'string'],
        ]);

        return $this->returnRules();
    }

    /**
     * Get company data
     *
     * @return array
     */
    public function companyData()
    {
        $data = $this->only([
            'company_name',
            'email',
            'phone_number',
            'vat_number',
            'commerce_chamber_number',
            'company_website_url',
        ]);
        $data['visiting_address'] = $this->visitingAddress();
        $data['invoicing_address'] = $this->invoicingAddress();

        return $data;
    }

    /**
     * Get visiting address data
     *
     * @return array
     */
    public function visitingAddress()
    {
        return [
            'address' => $this->input('visiting_address'),
            'house_number' => $this->input('visiting_address_house_number'),
            'house_number_suffix' => $this->input(
                'visiting_address_house_number_suffix'
            ),
            'zipcode' => $this->input('visiting_address_zipcode'),
            'city' => $this->input('visiting_address_city'),
            'province' => $this->input('visiting_address_province'),
        ];
    }

    /**
     * Get invoicing address data
     *
     * @return array
     */
    public function invoicingAddress()
    {
        return [
            'address' => $this->input('invoicing_address'),
            'house_number' => $this->input('invoicing_address_house_number'),
            'house_number_suffix' => $this->input(
                'invoicing_address_house_number_suffix'
            ),
            'zipcode' => $this->input('invoicing_address_zipcode'),
            'city' => $this->input('invoicing_address_city'),
            'province' => $this->input('invoicing_address_province'),
        ];
    }
}
