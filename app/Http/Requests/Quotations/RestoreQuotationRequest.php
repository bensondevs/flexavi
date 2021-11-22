<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;

class RestoreQuotationRequest extends FormRequest
{
    /**
     * Target quotation model container
     * 
     * @var \App\Models\Quotation
     */
    private $quotation;

    /**
     * Get target quotation from the request payload
     * 
     * @return \App\Models\Quotation
     */
    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id') ?: $this->input('id');
        return $this->quotation = Quotation::onlyTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('restore-quotation', $quotation);
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
