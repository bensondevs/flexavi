<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;

use App\Traits\RequestHasRelations;

class FindQuotationRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of loadable relationships
     * 
     * @var array
     */
    private $relationNames = [
        'with_appointment' => false,
        'with_works' => false,
        'with_customer' => true,
        'with_attachments' => false,
        'with_company' => false,
        'with_revisions' => false,
        'with_invoice' => false,
    ];

    /**
     * Found uotation model container
     * 
     * @var App\Models\Quotation|null
     */
    private $quotation;

    /**
     * Get quotation by supplied input of "id" or "quoattion_id"
     * 
     * @return \App\Models\Quotation|abort 404
     */
    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id') ?: $this->input('quotation_id');
        $relations = $this->relations();
        return $this->quotation = Quotation::with($relations)->findOrFail($id);
    }

    /**
     * Prepare input for validation.
     * 
     * This will prepare input to configure the loadable relationships
     * 
     * @return void
     */
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
