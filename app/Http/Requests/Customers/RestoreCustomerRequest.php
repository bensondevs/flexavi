<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Customer;

use App\Traits\CompanyInputRequest;

class RestoreCustomerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $trashedCustomer;

    public function getTrashedCustomer()
    {
        if ($this->trashedCustomer) return $this->trashedCustomer;

        $id = $this->input('id') ?: $this->input('customer_id');
        return $this->trashedCustomer = Customer::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getTrashedCustomer();
        return Gate::allows('restore-customer', $customer);
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