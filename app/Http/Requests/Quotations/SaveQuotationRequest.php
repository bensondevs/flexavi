<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\FloatValue;
use App\Rules\AmongStrings;
use App\Rules\UniqueWithConditions;

use App\Models\Customer;
use App\Models\Quotation;

use App\Enums\Quotation\QuotationStatus;

use App\Traits\CompanyInputRequest;

class SaveQuotationRequest extends FormRequest
{
    use CompanyInputRequest;

    private $customer;
    private $quotation;
    private $appointment;

    public function getCustomer()
    {   
        if ($this->customer) return $this->customer;

        if ($id = $this->input('customer_id')) {
            $this->customer = Customer::findOrFail($this->input('customer_id'));
        } else if ($this->appointment) {
            $appointment = $this->getAppointment();
            $this->customer = $appointment->customer;
        } else {
            abort(403, 'You need to specify customer or appointment.');
        }

        return $this->customer;
    }

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;
        
        $id = $this->input('id') ?: $this->input('quotation_id');
        return $this->quotation = $this->model = Quotation::findOrFail($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');        
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('POST')) {
            $customer = $this->getCustomer();
            if ($this->input('appointment_id')) {
                $appointment = $this->getAppointment();
                return Gate::allows('create-quotation-with-appointment', $customer, $appointment);
            }

            return Gate::allows('create-quotation', $customer);
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $quotation = $this->getQuotation();
            $customer = $this->getCustomer();

            if ($this->input('appointment_id')) {
                $appointment = $this->getAppointment();
                return Gate::allows('update-quotation-with-appointment', [$quotation, $customer, $appointment]);
            }

            return Gate::allows('update-quotation', [$quotation, $customer]);
        }

        return false;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'type' => (int) $this->input('type'),
            'damage_causes' => json_decode($this->input('damage_causes'), true),
            'expiry_date' => $this->input('expiry_date') ? 
                carbon()->parse($this->input('expiry_date')) :
                carbon()->now()->addDays(3),
            'vat_percentage' => floatval($this->input('vat_percentage')),
            'discount_amount' => floatval($this->input('discount_amount')),
            'payment_method' => (int) $this->input('payment_method'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'appointment_id' => ['string'],
            'customer_id' => ['string'],

            'type' => ['required', 'numeric', 'min:1', 'max:4'],
            'quotation_number' => ['required', 'alpha_dash', new UniqueWithConditions(new Quotation, [['company_id' => $this->getCompany()->id]])],
            'quotation_date' => ['required', 'date'],

            'contact_person' => ['required', 'string'],

            'address' => ['required', 'string'],
            'zip_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string', 'numeric'],
            
            'damage_causes' => ['required', 'array'],
            'quotation_description' => ['required', 'string'],

            'expiry_date' => ['date'],

            'vat_percentage' => [new FloatValue(true)],
            'discount_amount' => [new FloatValue(true)],
            'payment_method' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        return $this->returnRules();
    }

    public function quotationData()
    {
        $data = $this->ruleWithCompany();
        $data['customer_id'] = $this->getCustomer()->id;
        $data['status'] = QuotationStatus::Draft;
        return $data;
    }
}
