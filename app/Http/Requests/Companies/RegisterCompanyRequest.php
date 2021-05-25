<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\JsonArray;

use App\Models\Company;

class RegisterCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $request->user(); 

        return (! $user->owner) &&
            $user->hasRole('owner');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Company Data
            'visiting_addresss' => ['required', 'string', new JsonArray],
            'invoiving_address' => ['required', 'string', new JsonArray],
            'email' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'vat_number' => ['required', 'string'],
            'commerce_chamber_number' => ['required', 'string'],
            'company_logo' => ['required', 'file', 'mimes:jpg,jpeg,png,svg'],
            'company_website_url' => ['required', 'string'],

            // Owner Information
            'bank_name' => ['required', 'string', 'alpha_dash'],
            'bic_code' => ['required', 'alpha_dash'],
            'bank_account' => ['required', 'alpha_num'],
            'bank_holder_name' => ['required', 'string'],
        ];
    }

    public function companyData()
    {
        return $this->only([
            'visiting_addresss',
            'invoiving_address',
            'email',
            'phone_number',
            'vat_number',
            'commerce_chamber_number',
            'company_logo',
            'company_website_url',
        ]);
    }

    public function ownerData()
    {
        $ownerData = $this->only([
            'bank_name',
            'bic_code',
            'bank_account',
            'bank_holder_name',
        ]);
        $ownerData['user_id'] = $this->user()->id;
        $ownerData['is_prime_owner'] = 1;

        return $ownerData;
    }

    public function registerData()
    {
        return [
            'company' => $this->companyData(),
            'owner' => $this->ownerData(),
        ];
    }
}
