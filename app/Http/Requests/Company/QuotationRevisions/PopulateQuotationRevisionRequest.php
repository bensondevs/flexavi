<?php

namespace App\Http\Requests\Company\QuotationRevisions;

use App\Models\Quotation\QuotationRevision;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateQuotationRevisionRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * QuotationRevision object
     *
     * @var QuotationRevision|null
     */
    private $quotation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        $quotation = $this->getQuotation();

        return $user->hasCompanyPermission(
            $quotation->company_id,
            'view quotation revisions'
        );
    }

    /**
     * Get QuotationRevision based on supplied input
     *
     * @return QuotationRevision
     */
    public function getQuotation()
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id');

        return $this->quotation = QuotationRevision::findOrFail($id);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
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
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
