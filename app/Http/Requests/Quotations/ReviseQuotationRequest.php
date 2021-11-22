<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\FloatValue;
use App\Traits\InputRequest;
use App\Models\Quotation;

class ReviseQuotationRequest extends FormRequest
{
    use InputRequest;

    /**
     * Target quotation model container
     * 
     * @var \App\Models\Quotation 
     */
    private $quotation;

    /**
     * Get quotation from the input payload
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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('revise-quotation', $quotation);
    }

    /**
     * Prepare 
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'discount_amount' => floatval($this->input('discount_amount')),
            'payment_method' => (int) $this->input('payment_method'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'discount_amount' => [new FloatValue(true)],
            'payment_method' => ['integer', 'min:1', 'max:2'],
        ]);

        return $this->returnRules();
    }

    /**
     * Get revision data after validation
     * 
     * @return array
     */
    public function revisionData()
    {
        return $this->validated();
    }
}