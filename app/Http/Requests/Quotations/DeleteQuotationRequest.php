<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;

class DeleteQuotationRequest extends FormRequest
{
    private $quotation;

    public function getQuotation()
    {
        return $this->quotation = ($this->quotation) ?:
            Quotation::findOrFail($this->input('id'));
    }

    protected function prepareForValidation()
    {
        $this->merge(['force' => filter_var($this->input('force'), FILTER_VALIDATE_BOOLEAN)]);
    }

    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('delete-quotation', $quotation);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
