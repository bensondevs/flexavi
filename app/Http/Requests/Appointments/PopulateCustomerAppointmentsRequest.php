<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Customer;

use App\Enums\Appointment\AppointmentType;
use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentCancellationVault;

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
        return Gate::allows('view-any-appointment', $customer);
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
                'min:' . AppointmentType::Inspection, 
                'max:' . AppointmentType::PaymentReminder
            ],
            'status' => [
                'numeric', 
                'min:' . AppointmentStatus::Created, 
                'max:' . AppointmentStatus::Cancelled
            ],
            'cancellation_vault' => [
                'numeric', 
                'min:' . AppointmentCancellationVault::Roofer, 
                'max:' . AppointmentCancellationVault::Customer
            ],
        ];
    }

    public function options()
    {
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

        $options = $this->collectCompanyOptions();
        array_push($options['wheres'], [
            'column' => 'customer_id',
            'value' => $this->getCustomer()->id,
        ]);

        return $options;
    }
}
