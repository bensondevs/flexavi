<?php

namespace App\Http\Requests\Company\Customers;

use App\Models\Customer\Customer;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class RestoreCustomerRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Customer object
     *
     * @var Customer|null
     */
    private $trashedCustomer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getTrashedCustomer();

        return $this->user()
            ->fresh()
            ->can('restore-customer', $customer);
    }

    /**
     * Get Trashed Customer based on supplied input
     *
     * @return Customer
     */
    public function getTrashedCustomer()
    {
        if ($this->trashedCustomer) {
            return $this->trashedCustomer;
        }
        $id = $this->input('id') ?: $this->input('customer_id');

        return $this->trashedCustomer = Customer::withTrashed()->findOrFail(
            $id
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
