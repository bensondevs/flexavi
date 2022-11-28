<?php

namespace App\Http\Requests\Company\Appointments;

use App\Enums\Appointment\AppointmentCancellationVault;
use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentType;
use App\Models\Customer\Customer;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateCustomerAppointmentsRequest extends FormRequest
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
            ->can('view-any-appointment', $customer);
    }

    /**
     * Get Customer based on supplied input
     *
     * @return Customer
     */
    public function getCustomer()
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('customer_id');

        return $this->customer = $this->model = Customer::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => [
                'numeric',
                Rule::in(AppointmentType::getValues()),
            ],
            'status' => [
                'numeric',
                Rule::in(AppointmentStatus::getValues()),
            ],
            'cancellation_vault' => [
                'numeric',
                Rule::in(AppointmentCancellationVault::getValues()),
            ],
        ];
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
            'value' => $this->getCustomer()->id,
        ]);
        if ($type = $this->get('type')) {
            $this->addWhere([
                'column' => 'type',
                'operator' => '=',
                'value' => $type,
            ]);
        }
        if ($status = $this->get('status')) {
            $this->addWhere([
                'column' => 'status',
                'operator' => '=',
                'value' => $status,
            ]);
        }
        if ($cancellationVault = $this->get('cancellation_vault')) {
            $this->addWhere([
                'column' => 'cancellation_vault',
                'operator' => '=',
                'value' => $cancellationVault,
            ]);
        }
        if ($this->get('has_subs_only')) {
            $this->addWhereHas('subs');
        }

        return $this->collectCompanyOptions();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_string($this->get('has_subs_only'))) {
            $this->merge([
                'has_subs_only' => strtobool($this->get('has_subs_only')),
            ]);
        }
    }
}
