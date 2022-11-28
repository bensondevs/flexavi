<?php

namespace App\Http\Requests\Company\QuotationRevisions;

use App\Models\Quotation\Quotation;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveQuotationRevisionRequest extends FormRequest
{
    use InputRequest;

    /**
     * Quotation object
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
        $user = $this->user()->fresh();
        $quotation = $this->getQuotation();

        return $user->hasCompanyPermission(
            $quotation->company_id,
            'create quotation revisions'
        );
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
        $id = $this->input('id');

        return $this->quotation = Quotation::findOrFail($id);
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
