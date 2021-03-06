<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;

class DeleteQuotationRequest extends FormRequest
{
    /**
     * Target quotation model container
     * 
     * @var \App\Models\Quotation
     */
    private $quotation;

    /**
     * Get quotation from request payload
     * 
     * @return \App\Models\Quotation
     */
    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id') ?: $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Format input values before validations
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('delete-quotation', $quotation);
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
