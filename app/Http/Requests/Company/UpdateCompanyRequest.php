<?php

namespace App\Http\Requests\Company;

use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        return $user->can('edit-company', $this->getCompany());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "company_name" => "nullable|string" ,
            "email" => "nullable|email" ,
            "phone_number" => "nullable|string" ,
            "vat_number" => "nullable|numeric" ,
            "commerce_chamber_number" => "nullable|numeric" ,
            "company_website_url" => "nullable|string" ,

            "visiting_address.address" => "nullable|string" ,
            "visiting_address.house_number" => "nullable|numeric" ,
            "visiting_address.house_number_suffix" => "nullable|string" ,
            "visiting_address.province" => "nullable|string" ,
            "visiting_address.zipcode" => "nullable|numeric" ,
            "visiting_address.city" => "nullable|string" ,

            "invoicing_address.address" => "nullable|string" ,
            "invoicing_address.house_number" => "nullable|numeric" ,
            "invoicing_address.house_number_suffix" => "nullable|string" ,
            "invoicing_address.province" => "nullable|string" ,
            "invoicing_address.zipcode" => "nullable|numeric" ,
            "invoicing_address.city" => "nullable|string" ,
        ];
    }
}
