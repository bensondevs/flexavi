<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;

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
        $user = $this->user();
        if ($user->hasRole('admin')) return true;

        // Validate Action
        $company = $this->getCompany();
        if (! $this->isMethod('POST')) $quotation = $this->getQuotation();
        if (! $this->authorizeCompanyAction('quotations')) return false;

        // Validate Customer
        $customer = $this->getCustomer();
        $authorizeAction = $user->hasCompanyPermission($customer->company_id, 'view customer');
        if (! $authorizeAction) return false;

        // Validate Appointment if assigned
        if ($this->input('appointment_id')) {
            $appointment = $this->getAppointment();
            $authorizeAction = $user->hasCompanyPermission($appointment->company_id, 'view appointments');
        }

        return true;
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
            'quotation_type' => [
                'required', 
                'string', 
                new AmongStrings(Quotation::getTypes())
            ],
            'quotation_description' => ['required', 'string'],
            'quotation_document' => ['file', 'mimes:pdf', 'max:10000'],
            'expiry_date' => ['required', 'string', 'date'],
            'status' => [
                'required', 
                'string', 
                new AmongStrings(Quotation::getStatuses())
            ],
            'payment_method' => ['required', 'string'],
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
