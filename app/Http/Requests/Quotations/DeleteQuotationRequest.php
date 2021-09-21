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
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id') ?: $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
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
