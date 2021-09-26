<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;

use App\Traits\InputRequest;

class SendQuotationRequest extends FormRequest
{
    use InputRequest;

    private $quotation;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id') ?: $this->input('quotation_id');
        return $this->quotation = Quotation::find($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('send-quotation', $quotation);
    }

    protected function prepareForValidation()
    {
        if (! $this->has('destination')) {
            $quotation = $this->getQuotation();
            $this->merge(['destination' => $quotation->customer->email]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'destination' => ['string'],
            'text' => ['string'],
        ];
    }

    public function sendData()
    {
        return $this->validated();
    }
}
