<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Customer;

use App\Traits\CompanyInputRequest;

class RestoreCustomerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $deletedCustomer;

    public function getDeletedCustomer()
    {
        return $this->deletedCustomer = $this->model = $this->deletedCustomer ?:
            Customer::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getDeletedCustomer();
        return $this->checkCompanyPermission('restore company', $customer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
