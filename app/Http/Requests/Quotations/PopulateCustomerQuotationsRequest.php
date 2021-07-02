<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Customer;

class PopulateCustomerQuotationsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $customer;

    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('customer_id');
        return Customer::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();
        return Gate::allows('view-any-customer-quotation', $customer);
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

    public function options()
    {
        $this->addWhere([
            'column' => 'customer_id',
            'operator' => '=',
            'value' => $this->getCustomer()->id,
        ]);

        return $this->collectOptions();
    }
}
