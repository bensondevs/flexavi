<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Company;

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
        $user = $this->user();
        $company = request()->get('_company') ?: 
            $user->{$user->user_role}->company;

        if (! $company) {
            $this->company = new Company();
            return $user->hasPermissionTo('register companies');
        }

        $this->company = $this->model = $company;
        return $user->hasPermissionTo('edit companies');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            // Visiting Address
            'visiting_address' => ['required', 'string'],
            'visiting_address_house_number' => ['required', 'numeric'],
            'visiting_address_house_number_suffix' => ['string'],
            'visiting_address_zipcode' => ['required', 'numeric'],
            'visiting_address_city' => ['required', 'string'],

            // Invoicing Address
            'invoicing_address' => ['required', 'string'],
            'invoicing_address_house_number' => ['required', 'numeric'],
            'invoicing_address_house_number_suffix' => ['string'],
            'invoicing_address_zipcode' => ['required', 'numeric'],
            'invoicing_address_city' => ['required', 'string'],
            
            'email' => ['required', 'string', 'unique:companies,email'],
            'phone_number' => ['required', 'string', 'unique:companies,phone_number'],
            'vat_number' => ['required', 'string', 'unique:companies,vat_number'],
            'commerce_chamber_number' => ['numeric'],
            'company_website_url' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    public function visitingAddress()
    {
        return [
            'address' => $this->input('visiting_address'),
            'house_number' => $this->input('visiting_address_house_number'),
            'house_number_suffix' => $this->input('visiting_address_house_number_suffix'),
            'zipcode' => $this->input('visiting_address_zipcode'),
            'city' => $this->input('visiting_address_city'),
            'province' => $this->input('visiting_address_province'),
        ];
    }

    public function invoicingAddress()
    {
        return [
            'address' => $this->input('invoicing_address'),
            'house_number' => $this->input('invoicing_address_house_number'),
            'house_number_suffix' => $this->input('visiting_address_house_number_suffix'),
            'zipcode' => $this->input('invoicing_address_zipcode'),
            'city' => $this->input('invoicing_address_city'),
            'province' => $this->input('invoicing_address_province'),
        ];
    }

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
}
