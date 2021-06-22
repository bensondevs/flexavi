<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Quotation;

class CancelQuotationRequest extends FormRequest
{
    private $quotation;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $quotation = $this->getQuotation();

        return $user->hasCompanyPermission($quotation->company_id, 'cancel quotations');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'canceller' => ['required', 'string'],
            'cancellation_reason' => ['required', 'string'],
        ])

        return $this->returnRules();
    }
}
