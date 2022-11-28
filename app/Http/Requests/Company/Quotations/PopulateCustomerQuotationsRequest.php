<?php

namespace App\Http\Requests\Company\Quotations;

use App\Models\Customer\Customer;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCustomerQuotationsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Customer object
     *
     * @var Customer|null
     */
    private $customer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();

        return $this->user()
            ->fresh()
            ->can('view-any-customer-quotation', $customer);
    }

    /**
     * Get Customer based on supplid input
     *
     * @return Customer
     */
    public function getCustomer()
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('customer_id');

        return $this->customer = Customer::findOrFail($id);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'customer_id',
            'operator' => '=',
            'value' => $this->getCustomer()->id,
        ]);

        if ($keyword = $this->get('keyword', $this->get('search', null))) {
            $this->setSearch($keyword);
            $this->setSearchScope('table_scope_only');
        }

        return $this->collectOptions();
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
