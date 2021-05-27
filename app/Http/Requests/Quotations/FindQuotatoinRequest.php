<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Quotation;

class FindQuotatoinRequest extends FormRequest
{
    private $quotation;

    public function getQuotation()
    {
        return $this->quotation = ($this->quotation) ?:
            Quotation::findOrFail($this->input('id'));
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

        $action = ($this->isMethod('GET') ? 
            'view quotations' : 
            'delete quotations';

        return $user->hasCompanyPermission($quotation->company_id, $action);
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
}
