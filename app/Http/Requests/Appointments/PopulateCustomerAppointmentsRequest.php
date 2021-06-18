<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Customer;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCustomerAppointmentsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $customer;

    public function getCustomer()
    {
        return $this->customer = $this->model = $this->customer ?:
            Customer::findOrFail($this->input('customer_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();
        return $this->checkCompanyPermission('view appointments', $customer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'string'],
        ];
    }

    public function options()
    {
        $options = $this->collectCompanyOptions();
        array_push($options['wheres'], [
            'column' => 'customer_id',
            'value' => $this->getCustomer()->id,
        ]);

        return $options;
    }
}
