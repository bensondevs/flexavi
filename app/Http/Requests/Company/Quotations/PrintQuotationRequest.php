<?php

namespace App\Http\Requests\Company\Quotations;

use App\Models\Quotation\Quotation;
use Illuminate\Foundation\Http\FormRequest;

class PrintQuotationRequest extends FormRequest
{
    /**
     * Quotation object
     *
     * @var Quotation|null
     */
    private ?Quotation $quotation = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $quotation = $this->getQuotation();

        return $this->user()
            ->fresh()
            ->can('print-quotation', $quotation);
    }

    /**
     * Get Quotation based on supplied input
     *
     * @return ?Quotation
     */
    public function getQuotation(): ?Quotation
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id');

        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
