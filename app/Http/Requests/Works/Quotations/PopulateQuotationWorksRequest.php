<?php

namespace App\Http\Requests\Works\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

use App\Models\Quotation;

class PopulateQuotationWorksRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Quotation model container
     * 
     * @var \App\Models\Quotation|null
     */
    private $quotation;

    /**
     * Find Quotation or abort 404 for Gate parameter
     * 
     * @return \App\Models\Quotation
     */
    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
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
        return Gate::allows('view-any-work', $quotation);
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

    /**
     * Set options for get() query
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'quotation_id',
            'operator' => '=',
            'value' => $this->getQuotation()->id,
        ]);

        $this->setWiths(['quotation', 'contract']);

        return $this->collectOptions();
    }
}
