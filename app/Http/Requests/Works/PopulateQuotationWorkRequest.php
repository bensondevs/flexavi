<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

use App\Models\Quotation;

class PopulateQuotationWorkRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $quotation;

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
        $user = $this->user();
        $quotation = $this->getQuotation();

        return $user->hasCompanyPermission($quotation->company_id, 'view works');
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
            'operator' => '=',
            'value' => $this->getQuotation()->id,
        ]);

        return $this->collectOptions();
    }
}
