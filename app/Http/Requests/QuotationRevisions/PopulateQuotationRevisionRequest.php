<?php

namespace App\Http\Requests\QuotationRevisions;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

use App\Models\QuotationRevision;

class PopulateQuotationRevisionRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $quotation;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
        return $this->quotation = QuotationRevision::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $quotation = $this->getQuotation();

        return $user->hasCompanyPermission($quotation->company_id, 'view quotation revisions');
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

    public function options()
    {
        $this->addWhere([
            'column' => 'quotation_id',
            'value' => $this->getQuotation()->id,
        ]);

        return $this->collectOptions();
    }
}
