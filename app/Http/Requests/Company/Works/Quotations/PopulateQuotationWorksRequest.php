<?php

namespace App\Http\Requests\Company\Works\Quotations;

use App\Models\Quotation\Quotation;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateQuotationWorksRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Quotation model container
     *
     * @var Quotation|null
     */
    private $quotation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();

        return $this->user()
            ->fresh()
            ->can('view-any-work', $quotation);
    }

    /**
     * Get Quotation based on supplied input
     *
     * @return Quotation
     */
    public function getQuotation()
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id');

        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Set options for get() query
     *
     * @return array
     */
    public function options()
    {
        $this->addWhereHas('quotations', [
            [
                'column' => 'quotations.id',
                'value' => $this->getQuotation()->id,
            ],
        ]);

        return $this->collectOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
