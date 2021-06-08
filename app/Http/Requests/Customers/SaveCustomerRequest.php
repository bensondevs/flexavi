<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Customer;

use App\Rules\ZipCode;
use App\Rules\NotEqual;
use App\Rules\AmongStrings;
use App\Rules\CompanyOwned;

use App\Traits\CompanyInputRequest;

class SaveCustomerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $customer;

    public function getCustomer()
    {
        return $this->customer = $this->model = ($this->customer) ?
            $this->customer : 
            Customer::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $company = $this->getCompany();

        if ($this->isMethod('POST'))
            return $user->hasCompanyPermission($company->id, 'create customers');
    
        $customer = $this->getCustomer();
        return $user->hasCompanyPermission($customer->company_id, 'edit customers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $salutations = Customer::salutationValues();

        $this->setRules([
            'fullname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'salutation' => ['string', new AmongStrings($salutations)],
            'address' => ['required', 'string'],
            'house_number' => ['required', 'numeric'],
            'zipcode' => ['required', new ZipCode],
            'city' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'province' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['string', 'email', 'unique:customers,email'],
            'phone' => ['required', 'numeric', 'unique:customers,phone'],
            'second_phone' => ['numeric', new NotEqual('phone')],
        ]);

        return $this->returnRules();
    }
}
