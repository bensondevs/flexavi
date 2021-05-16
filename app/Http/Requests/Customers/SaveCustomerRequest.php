<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Customer;

use App\Rules\NotEqual;
use App\Rules\AmongStrings;
use App\Rules\CompanyOwned;

use App\Traits\InputRequest;

class SaveCustomerRequest extends FormRequest
{
    use InputRequest;

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
        if ($this->isMethod('POST'))
            return true;
    
        $customer = $this->getCustomer();
        return auth()->user()
            ->hasCompanyPermission($customer->company_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string', new CompanyOwned()],
            'fullname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'salutation' => ['required', 'string', new AmongStrings(['Mr.', 'Mrs.'])],
            'address' => ['required', 'string'],
            'house_number' => ['required', 'numeric'],
            'zipcode' => ['required', 'string', 'numeric'],
            'city' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'province' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'string', 'email', 'unique:customers,email'],
            'phone' => ['required', 'numeric', 'unique:customers,phone'],
            'second_phone' => ['numeric', new NotEqual('phone')],
        ]);

        return $this->returnRules();
    }
}
