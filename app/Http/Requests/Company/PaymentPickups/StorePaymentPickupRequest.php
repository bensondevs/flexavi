<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\{Appointment\Appointment};
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentPickupRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Selected appointment set for payment pickup
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('create-payment-pickup', $appointment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'appointment_id' => ['required', 'string', Rule::exists('appointments', 'id')],
            'company_id' => ['required', 'string', Rule::exists('companies', 'id')],
            'items.*' => ['required', 'array'],
            'items.*.invoice_id' => ['required', 'string', Rule::exists('invoices', 'id')],
            'items.*.pickup_amount' => ['required', 'numeric',],
            'items.*.note' => ['nullable', 'string',],
            'items.*.payment_term_ids' => ['required', 'array'],
            'items.*.payment_term_ids.*' => ['required', 'string', Rule::exists('payment_terms', 'id')],
        ];
    }

    /**
     * Get payment pickup data
     *
     * @return array
     */
    public function paymentPickupData(): array
    {
        return [
            'appointment_id' => $this->input('appointment_id'),
            'company_id' => $this->input('company_id'),
        ];
    }

    /**
     * Get payment pickup items data
     *
     * @return array
     */
    public function paymentPickupItemsData(): array
    {
        if ($input['items'] = $this->validated()['items'] ?? false) {
            $data['items'] = $input['items'];
            return $data['items'];
        }
        return [];
    }

    /**
     * Prepare inputted columns before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $appointment = $this->getAppointment();
        $this->merge([
            'company_id' => $appointment->company_id
        ]);
    }

    /**
     * Get Appointment based on supplied input
     *
     * @return Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) {
            return $this->appointment;
        }
        $id = $this->input('appointment_id');

        return $this->appointment = Appointment::findOrFail($id);
    }
}
