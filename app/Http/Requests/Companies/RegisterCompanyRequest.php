<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Company;

use App\Traits\CompanyInputRequest;

class RegisterCompanyRequest extends FormRequest
{
    use CompanyInputRequest;

    private $owner;

    public function getOwner()
    {
        return $this->owner = $this->owner ?: 
            $this->user()->owner;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $request->user(); 

        return (! $user->owner) && $user->hasRole('owner');
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
            'visiting_addresss_street' => ['required', 'string'],
            'visiting_addresss_house_number' => ['required', 'string'],
            'visiting_addresss_house_number_suffix' => ['string'],
            'visiting_addresss_zip_code' => ['required', 'string'],
            'visiting_addresss_city' => ['required', 'string'],

            // Invoicing Address
            'invoicing_addresss_street' => ['required', 'string'],
            'invoicing_addresss_house_number' => ['required', 'string'],
            'invoicing_addresss_house_number_suffix' => ['string'],
            'invoicing_addresss_zip_code' => ['required', 'string'],
            'invoicing_addresss_city' => ['required', 'string'],
            
            'email' => ['required', 'string', 'unique:companies,email'],
            'phone_number' => ['required', 'string', 'unique:companies,phone_number'],
            'vat_number' => ['required', 'string', 'unique:companies,vat_number'],
            'commerce_chamber_number' => ['required', 'string'],
            'company_website_url' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    public function visitingAddress()
    {
        return json_encode([
            'street' => $this->input('visiting_addresss_street'),
            'house_number' => $this->input('visiting_addresss_house_number'),
            'house_number_suffix' => $this->input('visiting_addresss_house_number_suffix'),
            'zip_code' => $this->input('visiting_addresss_zip_code'),
            'city' => $this->input('visiting_addresss_city'),
        ]);
    }

    public function invoicingAddress()
    {
        return json_encode([
            'street' => $this->input('invoicing_addresss_street'),
            'house_number' => $this->input('invoicing_addresss_house_number'),
            'house_number_suffix' => $this->input('visiting_addresss_house_number_suffix'),
            'zip_code' => $this->input('invoicing_addresss_zip_code'),
            'city' => $this->input('invoicing_addresss_city'),
        ]);
    }

    public function companyData()
    {
        $data = $this->only([
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
