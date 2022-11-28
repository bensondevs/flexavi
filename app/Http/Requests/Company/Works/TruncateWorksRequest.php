<?php

namespace App\Http\Requests\Company\Works;

use App\Models\Quotation\Quotation;
use Illuminate\Foundation\Http\FormRequest;

class TruncateWorksRequest extends FormRequest
{
    /**
     * Quotation model container
     *
     * @var Quotation|null
     */
    protected $quotation;

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
            ->can('truncate-work', $quotation);
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
