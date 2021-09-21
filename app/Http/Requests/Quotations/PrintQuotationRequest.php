<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;

class PrintQuotationRequest extends FormRequest
{
    private $quotation;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id') ?: $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('print-quotation', $quotation);
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
