<?php

namespace App\Http\Requests\Company\Quotations;

use App\Models\Quotation\Quotation;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateQuotationLogsRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Found Quotation model container
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
        return $this->user()->fresh()->can(
            'view-any-quotation-log',
            $quotation,
        );
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
     * Populate options of request
     *
     * @return array
     */
    public function options(): array
    {
        $this->addWhere([
            'column' => 'quotation_id',
            'value' => $this->getQuotation()->id,
        ]);
        return $this->collectOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
