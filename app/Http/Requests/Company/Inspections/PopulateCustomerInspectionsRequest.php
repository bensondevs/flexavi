<?php

namespace App\Http\Requests\Company\Inspections;

use App\Models\Customer\Customer;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCustomerInspectionsRequest extends FormRequest
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
            ->can('view-any-customer-inspection', $customer);
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

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if (strtobool($this->input('with_appointment'))) {
            $this->addWith('appointment.customer');
        }

        if (strtobool($this->input('with_pictures'))) {
            $this->addWith('pictures.works');
        }

        if (strtobool($this->input('with_customer'))) {
            $this->addWith('customer');
        }

        $this->addWhereHas('appointment', [
            [
                'column' => 'customer_id',
                'operator' => '=',
                'value' => $this->getCustomer()->id,
            ]
        ]);

        return $this->collectCompanyOptions();
    }
}
