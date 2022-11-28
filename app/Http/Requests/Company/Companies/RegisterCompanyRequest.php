<?php

namespace App\Http\Requests\Company\Companies;

use App\Models\Owner\Owner;
use App\Traits\CompanyInputRequest;
use Axiom\Rules\TelephoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Owner object
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $owner = $this->getOwner();

        return $this->user()
            ->fresh()
            ->can('register-company', $owner);
    }

    /**
     * Get Owner based on supplied input
     *
     * @return Owner
     */
    public function getOwner()
    {
        if ($this->owner) {
            return $this->owner;
        }

        return $this->owner = $this->user()->fresh()->owner;
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
            'visiting_address_province' => ['required', 'string'],

            // Invoicing Address
            'invoicing_address' => ['required', 'string'],
            'invoicing_address_house_number' => ['required', 'numeric'],
            'invoicing_address_house_number_suffix' => ['string'],
            'invoicing_address_zipcode' => ['required', 'numeric'],
            'invoicing_address_city' => ['required', 'string'],
            'invoicing_address_province' => ['required', 'string'],

            'company_name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:companies,email'],
            'phone_number' => [
                'required',
                new TelephoneNumber(),
                'unique:companies,phone_number',
            ],
            'vat_number' => [
                'required',
                'string',
                'unique:companies,vat_number',
            ],
            'commerce_chamber_number' => ['numeric'],
            'company_website_url' => ['string'],
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
                'visiting_address_house_number_suffix'
            ),
            'zipcode' => $this->input('invoicing_address_zipcode'),
            'city' => $this->input('invoicing_address_city'),
            'province' => $this->input('invoicing_address_province'),
        ];
    }
}
