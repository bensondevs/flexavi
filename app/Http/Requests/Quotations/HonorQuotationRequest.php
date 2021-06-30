<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\FloatValue;

use App\Traits\InputRequest;

class HonorQuotationRequest extends FormRequest
{
    use InputRequest;

    private $quotation;

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
}
