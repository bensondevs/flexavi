<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AmongStrings;
use App\Rules\CompanyOwned;

use App\Models\Quotation;

use App\Traits\CompanyInputRequest;

class SaveQuotationRequest extends FormRequest
{
    use CompanyInputRequest;

    private $quotation;

    public function getQuotation()
    {
        return $this->quotation = $this->model = $this->quotation ?:
            Quotation::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        $this->merge(['creator_id' => $user->id]);
        return $user->hasCompanyPermission(
            $this->getQuotation()->company_id
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'customer_id' => ['required', 'string', 'exists:customers,id'],
            
            'subject' => ['required', 'string'],
            'quotation_number' => ['required', 'string', 'alpha_num'],
            'quotation_type' => [
                'required', 
                'string', 
                new AmongStrings(Quotation::getTypes())
            ],
            'quotation_description' => ['required', 'string', 'alpa_dash'],
            'pdf_url' => ['required', 'string', 'url'],
            'expiry_date' => ['required', 'string', 'date'],
            'status' => [
                'required', 
                'string', 
                new AmongStrings(Quotation::getStatuses())
            ],
            'payment_method' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
