<?php

namespace App\Http\Requests\QuotationRevisions;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Quotation;

class SaveQuotationRevisionRequest extends FormRequest
{
    use InputRequest;

    private $quotation;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id');
        return $this->quotation = Quotation::findOrFail($id);
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

        return $user->hasCompanyPermission($quotation->company_id, 'create quotation revisions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*return [
            'quotation_number' => [''],
            'quotation_type' => [''],
            'quotation_description' => [''],
            'expiry_date' => [''],
            'payment_method' => [''],
        ];*/
    }

    public function revisionData()
    {

    }
}
