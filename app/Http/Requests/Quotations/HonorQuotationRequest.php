<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\FloatValue;

use App\Traits\InputRequest;

use App\Models\Quotation;

class HonorQuotationRequest extends FormRequest
{
    use InputRequest;

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
        $quotation = $this->getQuotation();
        return Gate::allows('honor-quotation', $quotation);
    }

    protected function prepareForValidation()
    {
        $this->merge(['discount_amount' => floatval($this->input('discount_amount'))]);
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
        ]);

        return $this->returnRules();
    }

    public function honorData()
    {
        return $this->onlyInRules();
    }
}
