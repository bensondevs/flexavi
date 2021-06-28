<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\AmongStrings;
use App\Rules\CompanyOwned;

use App\Models\Customer;
use App\Models\Quotation;

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

        return $this->customer = Customer::findOrFail($this->input('customer_id'));
    }

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;
        
        $id = $this->input('id') ?: $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
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
        $customer = $this->getCustomer();

        if ($this->isMethod('POST')) {
            if ($this->input('appointment_id')) {
                $appointment = $this->getAppointment();
                return Gate::allows('create-quotation-with-appointment', $customer, $appointment);
            }

            return Gate::allows('create-quotation', $customer);
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $quotation = $this->getQuotation();

            if ($this->input('appointment_id')) {
                $appointment = $this->getAppointment();
                return Gate::allows('update-quotation-with-appointment', $quotation, $customer, $appointment);
            }

            return Gate::allows('update-quotation', $quotation, $customer);
        }

        return false;
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
            'subject' => ['required', 'string'],
            'quotation_number' => ['required', 'string', 'alpha_num'],
            'quotation_type' => ['required', 'string', 'min:1', 'max:4'],
            'quotation_description' => ['required', 'string'],
            'quotation_document' => ['file', 'mimes:pdf', 'max:10000'],
            'expiry_date' => ['required', 'string', 'date'],
            'status' => ['required', 'integer', 'min:1', 'max:5'],
            'amount' => ['required', 'integer'],
            'payment_method' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        if ($this->isMethod('POST')) {
            // Upload file
            $documentRule = $this->rules['quotation_document'];
            array_push($documentRule, 'required');
            $this->addRule('quotation_document', $documentRule);
        }

        return $this->returnRules();
    }

    public function quotationData()
    {
        $data = $this->ruleWithCompany();
        $data['creator_id'] = $this->user()->id;
        $data['customer_id'] = $this->getCustomer()->id;
        unset($data['quotation_document']);
        return $data;
    }
}
