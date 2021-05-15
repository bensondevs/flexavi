<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AmongStrings;

use App\Models\Quotation;

class SaveQuotationRequest extends FormRequest
{
    private $quotation;

    public function getQuotation()
    {
        return $this->quotation = $this->quotation ?:
            Quotation::findOrFail(request()->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        request()->merge(['creator_id' => $user->id]);
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
        $rules = [
            'company_id' => ['required', 'string'],
            'customer_id' => ['required', 'string', 'exists:customers'],
            
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
        ];

        return $rules;
    }
}
