<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;

use App\Traits\RequestHasRelations;

class FindQuotationRequest extends FormRequest
{
    use RequestHasRelations;

    private $relationNames = [
        'with_appointment' => true,
        'with_works' => true,
        'with_customer' => true,
        'with_attachments' => true,
        'with_company' => false,
        'with_revisions' => false,
        'with_invoice' => false,
    ];

    private $quotation;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id') ?: $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('view-quotation', $quotation);
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
}
